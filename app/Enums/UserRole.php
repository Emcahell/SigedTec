<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Solicitante = 'solicitante';

    /**
     * Human readable label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador (Soporte Técnico)',
            self::Solicitante => 'Solicitante (Jefe de Oficina)',
        };
    }

    /**
     * Path the user is sent to after authenticating.
     */
    public function homePath(): string
    {
        return match ($this) {
            self::Admin => '/dashboard',
            self::Solicitante => '/solicitudes',
        };
    }
}
