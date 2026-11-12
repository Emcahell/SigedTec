<?php

namespace App\Http\Controllers;

use App\Enums\EquipmentStatus;
use App\Enums\UserRole;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Equipment;
use App\Models\RequestItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * List the users registered in the system.
     */
    public function index(): View
    {
        return view('users.index', [
            'users' => User::orderBy('role')->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form used by the administrator to register a requester.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a new requester account with the password set by the administrator.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            ...$request->validated(),
            'role' => UserRole::Solicitante->value,
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'El usuario solicitante fue registrado correctamente.');
    }

    /**
     * Show the form used by the administrator to edit a requester.
     */
    public function edit(User $user): View
    {
        $this->ensureRequester($user);

        return view('users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update a requester account. The password is only changed when provided.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->ensureRequester($user);

        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'El usuario solicitante fue actualizado correctamente.');
    }

    /**
     * Delete a requester account, releasing the equipment assigned to them.
     * Administrator accounts can never be deleted.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'No es posible eliminar una cuenta de administrador.');
        }

        DB::transaction(function () use ($user) {
            $equipmentIds = RequestItem::query()
                ->whereHas('request', fn ($query) => $query->where('user_id', $user->id))
                ->pluck('equipment_id');

            Equipment::whereIn('id', $equipmentIds)
                ->update(['estado' => EquipmentStatus::Disponible->value]);

            $user->delete();
        });

        return redirect()
            ->route('users.index')
            ->with('success', 'El usuario solicitante fue eliminado correctamente.');
    }

    /**
     * Abort when the target account is not a requester.
     */
    private function ensureRequester(User $user): void
    {
        abort_unless($user->isSolicitante(), 403);
    }
}
