<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

/*
 * Rutas administrativas: reservadas al rol admin (Soporte Técnico).
 * Incluye el dashboard, el CRUD de categorías y equipos y la gestión
 * de solicitudes (aprobación y rechazo).
 */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'disponibles' => App\Enums\EquipmentStatus::Disponible->value
                ? App\Models\Equipment::where('estado', App\Enums\EquipmentStatus::Disponible->value)->count()
                : 0,
            'asignados' => App\Models\Equipment::where('estado', App\Enums\EquipmentStatus::Asignado->value)->count(),
            'mantenimiento' => App\Models\Equipment::where('estado', App\Enums\EquipmentStatus::Mantenimiento->value)->count(),
            'total' => App\Models\Equipment::count(),
            'solicitudes_pendientes' => App\Models\Request::where('status', App\Models\Request::PENDING_STATUS)->count(),
        ];
        return view('dashboard', $stats);
    })->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('equipments', EquipmentController::class);

    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');

    Route::get('/requests/{solicitud}/aprobar', [RequestController::class, 'approveForm'])
        ->name('requests.approve');
    Route::post('/requests/{solicitud}/aprobar', [RequestController::class, 'approve'])
        ->name('requests.approve.store');

    Route::get('/requests/{solicitud}/rechazar', [RequestController::class, 'rejectForm'])
        ->name('requests.reject');
    Route::post('/requests/{solicitud}/rechazar', [RequestController::class, 'reject'])
        ->name('requests.reject.store');
});

/*
 * Rutas del solicitante (Jefe de Oficina): creación y seguimiento
 * de sus propias solicitudes.
 */
Route::middleware(['auth', 'role:solicitante'])->group(function () {
    Route::get('/requests/mine', [RequestController::class, 'myRequests'])->name('requests.mine');
    Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
});

/*
 * Detalle de una solicitud: el solicitante solo accede a las suyas,
 * el administrador accede a todas (validado en el controlador).
 */
Route::middleware('auth')->group(function () {
    Route::get('/requests/{solicitud}', [RequestController::class, 'show'])->name('requests.show');
});

Route::get('/componentes-demo', function () {
    return view('components-demo');
})->name('components.demo');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name(
        'profile.edit',
    );
    Route::patch('/profile', [ProfileController::class, 'update'])->name(
        'profile.update',
    );
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name(
        'profile.destroy',
    );
});

require __DIR__.'/auth.php';