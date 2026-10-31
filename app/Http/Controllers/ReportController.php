<?php

namespace App\Http\Controllers;

use App\Enums\EquipmentStatus;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Request as EquipmentRequest;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display the reports index with assignment history and filters.
     */
    public function index(Request $request): View
    {
        $filtros = $request->only(['oficina', 'categoria', 'fecha_inicio', 'fecha_fin']);

        $query = RequestItem::with(['request.user', 'request.category', 'equipment.category']);

        // Filtro por oficina
        if ($request->filled('oficina')) {
            $query->where('request.office', $request->oficina);
        }

        // Filtro por categoría
        if ($request->filled('categoria') && $request->integer('categoria')) {
            $query->where('request.category_id', $request->integer('categoria'));
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_inicio')) {
            $query->where('request.created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->where('request.created_at', '<=', $request->fecha_fin);
        }

        $asignaciones = $query->latest()->paginate(20);

        $categories = Category::orderBy('nombre')->get();

        return view('reports.index', [
            'asignaciones' => $asignaciones,
            'categories' => $categories,
            'filtros' => $filtros,
        ]);
    }
}