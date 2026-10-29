<?php

namespace App\Http\Controllers;

use App\Enums\EquipmentStatus;
use App\Enums\RequestStatus;
use App\Http\Requests\StoreRequestRequest;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Request as EquipmentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RequestController extends Controller
{
    /**
     * List the requests for the administrator, filtered by status.
     * Eager loading avoids the N+1 problem on requester, category and items.
     */
    public function index(HttpRequest $httpRequest): View
    {
        $filtro = $httpRequest->input('estado', RequestStatus::Pendiente->value);
        $status = $filtro === '' ? null : RequestStatus::tryFrom((string) $filtro);

        $solicitudes = EquipmentRequest::with(['user', 'category', 'items.equipment'])
            ->when($status !== null, fn ($query) => $query->where('status', $status->value))
            ->latest()
            ->get();

        return view('requests.admin_index', [
            'solicitudes' => $solicitudes,
            'status' => $status,
            'estados' => RequestStatus::options(),
            'pendientes' => EquipmentRequest::where('status', RequestStatus::Pendiente->value)->count(),
        ]);
    }

    /**
     * Show the form used by the requester to create a new request.
     */
    public function create(): View
    {
        return view('requests.create', [
            'categories' => Category::withCount([
                'equipments as disponibles_count' => fn ($query) => $query->where(
                    'estado',
                    EquipmentStatus::Disponible->value,
                ),
            ])->orderBy('nombre')->get(),
        ]);
    }

    /**
     * Persist a new request, always in "pendiente" status.
     */
    public function store(StoreRequestRequest $request): RedirectResponse
    {
        $solicitud = EquipmentRequest::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
            'status' => RequestStatus::Pendiente->value,
        ]);

        return redirect()
            ->route('requests.show', $solicitud)
            ->with('success', 'La solicitud fue registrada y quedó pendiente de aprobación.');
    }

    /**
     * History of the authenticated requester.
     */
    public function myRequests(): View
    {
        $solicitudes = EquipmentRequest::with(['category', 'items.equipment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('requests.my_requests', [
            'solicitudes' => $solicitudes,
        ]);
    }

    /**
     * Show one request. Requesters can only see their own requests,
     * administrators render the management view instead.
     */
    public function show(EquipmentRequest $solicitud): View
    {
        $user = auth()->user();

        if ($user->isSolicitante() && $solicitud->user_id !== $user->id) {
            abort(403, 'No tiene permisos para consultar esta solicitud.');
        }

        $solicitud->load(['user', 'category', 'items.equipment']);

        if ($user->isAdmin()) {
            return view('requests.admin_show', [
                'solicitud' => $solicitud,
                'disponibles' => Equipment::where('category_id', $solicitud->category_id)
                    ->where('estado', EquipmentStatus::Disponible->value)
                    ->count(),
            ]);
        }

        return view('requests.show', [
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * Form where the administrator picks the equipment to assign.
     */
    public function approveForm(EquipmentRequest $solicitud): View|RedirectResponse
    {
        if (! $solicitud->isPending()) {
            return redirect()
                ->route('requests.show', $solicitud)
                ->with('error', 'La solicitud ya fue procesada y no puede aprobarse nuevamente.');
        }

        $solicitud->load(['user', 'category', 'items.equipment']);

        return view('requests.approve', [
            'solicitud' => $solicitud,
            'equipos' => Equipment::where('category_id', $solicitud->category_id)
                ->where('estado', EquipmentStatus::Disponible->value)
                ->orderBy('codigo_patrimonial')
                ->get(),
        ]);
    }

    /**
     * Approve the request inside a database transaction: the selected
     * equipment is linked to the request, marked as assigned and the
     * request changes to "aprobada" together or not at all.
     */
    public function approve(HttpRequest $request, EquipmentRequest $solicitud): RedirectResponse
    {
        $data = $request->validate([
            'equipment_ids' => ['required', 'array'],
            'equipment_ids.*' => ['integer', 'distinct'],
        ], [
            'equipment_ids.required' => 'Debe seleccionar los equipos que se asignarán a la solicitud.',
            'equipment_ids.*.distinct' => 'Un equipo no puede seleccionarse dos veces.',
        ], [
            'equipment_ids' => 'equipos',
        ]);

        $equipmentIds = array_map('intval', $data['equipment_ids']);

        DB::transaction(function () use ($solicitud, $equipmentIds): void {
            $locked = EquipmentRequest::whereKey($solicitud->getKey())->lockForUpdate()->firstOrFail();

            if (! $locked->isPending()) {
                throw ValidationException::withMessages([
                    'equipment_ids' => 'Esta solicitud ya fue procesada.',
                ]);
            }

            if (count($equipmentIds) !== $locked->quantity) {
                throw ValidationException::withMessages([
                    'equipment_ids' => "Debe seleccionar exactamente {$locked->quantity} equipo(s) para esta categoría.",
                ]);
            }

            $equipos = Equipment::whereIn('id', $equipmentIds)
                ->where('category_id', $locked->category_id)
                ->where('estado', EquipmentStatus::Disponible->value)
                ->lockForUpdate()
                ->get();

            if ($equipos->count() !== count($equipmentIds)) {
                throw ValidationException::withMessages([
                    'equipment_ids' => 'Uno o más equipos seleccionados ya no están disponibles o no pertenecen a la categoría solicitada.',
                ]);
            }

            $assignedAt = now();

            foreach ($equipos as $equipo) {
                $locked->items()->create([
                    'equipment_id' => $equipo->getKey(),
                    'assigned_at' => $assignedAt,
                ]);

                $equipo->update(['estado' => EquipmentStatus::Asignado->value]);
            }

            $locked->update(['status' => RequestStatus::Aprobada->value]);
        });

        return redirect()
            ->route('requests.show', $solicitud)
            ->with('success', 'La solicitud fue aprobada y los equipos quedaron asignados.');
    }

    /**
     * Form where the administrator writes the rejection reason.
     */
    public function rejectForm(EquipmentRequest $solicitud): View|RedirectResponse
    {
        if (! $solicitud->isPending()) {
            return redirect()
                ->route('requests.show', $solicitud)
                ->with('error', 'La solicitud ya fue procesada y no puede rechazarse nuevamente.');
        }

        $solicitud->load(['user', 'category']);

        return view('requests.reject', [
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * Reject the request, storing the reason given by the administrator.
     */
    public function reject(HttpRequest $request, EquipmentRequest $solicitud): RedirectResponse
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'El motivo del rechazo es obligatorio.',
            'rejection_reason.max' => 'El motivo no debe superar los 500 caracteres.',
        ], [
            'rejection_reason' => 'motivo del rechazo',
        ]);

        DB::transaction(function () use ($solicitud, $data): void {
            $locked = EquipmentRequest::whereKey($solicitud->getKey())->lockForUpdate()->firstOrFail();

            if (! $locked->isPending()) {
                throw ValidationException::withMessages([
                    'rejection_reason' => 'Esta solicitud ya fue procesada.',
                ]);
            }

            $locked->update([
                'status' => RequestStatus::Rechazada->value,
                'rejection_reason' => $data['rejection_reason'],
            ]);
        });

        return redirect()
            ->route('requests.show', $solicitud)
            ->with('success', 'La solicitud fue rechazada y se registró el motivo.');
    }
}
