<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Interprets the stored role as a UserRole enum and normalizes
     * whatever is assigned to it before it reaches the database.
     */
    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): ?UserRole => $value === null
                ? null
                : UserRole::from(is_scalar($value) ? (string) $value : $value->value),
            set: fn (mixed $value): string => $value instanceof UserRole
                ? $value->value
                : UserRole::from(strtolower(trim((string) $value)))->value,
        );
    }

    /**
     * Determine if the user is an administrator (Soporte Técnico).
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Determine if the user is a requester (Jefe de Oficina).
     */
    public function isSolicitante(): bool
    {
        return $this->role === UserRole::Solicitante;
    }
}
