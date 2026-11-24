<?php

namespace App\Http\Controllers;

use App\Enums\EquipmentStatus;
use App\Models\Equipment;
use App\Models\Request as EquipmentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the administrator dashboard with real-time statistics.
     */
    public function index(): View
    {
        $stats = [
            'disponibles' => Equipment::query()->where('estado', EquipmentStatus::Disponible->value)->count(),
            'asignados' => Equipment::query()->where('estado', EquipmentStatus::Asignado->value)->count(),
            'mantenimiento' => Equipment::query()->where('estado', EquipmentStatus::Mantenimiento->value)->count(),
            'total' => Equipment::query()->count(),
            'solicitudes_pendientes' => EquipmentRequest::query()->where('status', EquipmentRequest::PENDING_STATUS)->count(),
        ];

        return view('dashboard', $stats);
    }

    /**
     * Get equipment status counts as JSON for chart rendering.
     */
    public function statsJson(): JsonResponse
    {
        $stats = [
            'disponibles' => Equipment::query()->where('estado', EquipmentStatus::Disponible->value)->count(),
            'asignados' => Equipment::query()->where('estado', EquipmentStatus::Asignado->value)->count(),
            'mantenimiento' => Equipment::query()->where('estado', EquipmentStatus::Mantenimiento->value)->count(),
        ];

        return $this->json($stats);
    }
}
