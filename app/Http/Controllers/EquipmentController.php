<?php

namespace App\Http\Controllers;

use App\Enums\EquipmentStatus;
use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    /**
     * List the equipment, optionally filtered by category and status.
     * The category relation is eager loaded to avoid N+1 queries.
     */
    public function index(Request $request): View
    {
        $filtros = $request->only(['categoria', 'estado']);
        $estado = EquipmentStatus::tryFrom($request->input('estado'));
        $categoria = $request->filled('categoria') ? $request->integer('categoria') : null;

        $equipments = Equipment::with('category')
            ->when($categoria !== null, fn ($query) => $query->where('category_id', $categoria))
            ->when($estado !== null, fn ($query) => $query->where('estado', $estado->value))
            ->orderBy('codigo_patrimonial')
            ->get();

        return view('equipments.index', [
            'equipments' => $equipments,
            'categories' => Category::orderBy('nombre')->get(),
            'estados' => EquipmentStatus::options(),
            'filtros' => $filtros,
        ]);
    }

    /**
     * Show the form to register a new equipment.
     */
    public function create(): View
    {
        return view('equipments.create', [
            'categories' => Category::orderBy('nombre')->get(),
            'estados' => EquipmentStatus::options(),
        ]);
    }

    /**
     * Persist a new equipment.
     */
    public function store(StoreEquipmentRequest $request): RedirectResponse
    {
        Equipment::create($request->validated());

        return redirect()
            ->route('equipments.index')
            ->with('success', 'El equipo fue registrado correctamente.');
    }

    /**
     * Show the details of one equipment.
     */
    public function show(Equipment $equipment): View
    {
        return view('equipments.show', [
            'equipment' => $equipment->load('category'),
        ]);
    }

    /**
     * Show the form to edit an equipment.
     */
    public function edit(Equipment $equipment): View
    {
        return view('equipments.edit', [
            'equipment' => $equipment,
            'categories' => Category::orderBy('nombre')->get(),
            'estados' => EquipmentStatus::options(),
        ]);
    }

    /**
     * Update an existing equipment.
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment): RedirectResponse
    {
        $equipment->update($request->validated());

        return redirect()
            ->route('equipments.index')
            ->with('success', 'El equipo fue actualizado correctamente.');
    }

    /**
     * Logically delete an equipment (soft delete).
     */
    public function destroy(Equipment $equipment): RedirectResponse
    {
        $equipment->delete();

        return redirect()
            ->route('equipments.index')
            ->with('success', 'El equipo fue eliminado correctamente.');
    }
}
