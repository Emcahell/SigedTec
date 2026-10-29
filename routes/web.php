<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
 * Rutas administrativas: reservadas al rol admin (Soporte Técnico).
 */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
 * Rutas de solicitudes: reservadas al rol solicitante (Jefe de Oficina).
 */
Route::middleware(['auth', 'role:solicitante'])->group(function () {
    Route::get('/solicitudes', function () {
        return view('solicitudes.index');
    })->name('solicitudes.index');
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
