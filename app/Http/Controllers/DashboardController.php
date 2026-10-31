<?php

namespace App\Http\Controllers;

use App\Enums\EquipmentStatus;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Request as EquipmentRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the administrator dashboard with real-time statistics.
     */
    public function index(): View
    {
        $stats = [
            'disponibles' => Equipment::where('estado', EquipmentStatus::Disponible->value)->count(),
            'asignados' => Equipment::where('estado', EquipmentStatus::Asignado->value)->count(),
            'mantenimiento' => Equipment::where('estado', EquipmentStatus::Mantenimiento->value)->count(),
            'total' => Equipment::count(),
            'solicitudes_pendientes' => EquipmentRequest::where('status', EquipmentRequest::PENDING_STATUS)->count(),
        ];

        return view('dashboard', $stats);
    }

    /**
     * Get equipment status counts as JSON for chart rendering.
     */
    public function statsJson(): \Illuminate\Http\JsonResponse
    {
        $stats = [
            'disponibles' => Equipment::where('estado', EquipmentStatus::Disponible->value)->count(),
            'asignados' => Equipment::where('estado', EquipmentStatus::Asignado->value)->count(),
            'mantenimiento' => Equipment::where('estado', EquipmentStatus::Mantenimiento->value)->count(),
        ];

        return $this->json($stats);
    }
}