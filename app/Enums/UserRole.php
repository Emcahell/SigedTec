<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = "admin";
    case Solicitante = "solicitante";

    public function label(): string
    {
        return match ($this) {
            self::Admin => "Administrador (Soporte Técnico)",
            self::Solicitante => "Solicitante (Jefe de Oficina)",
        };
    }

    public function homePath(): string
    {
        return match ($this) {
            self::Admin => "/dashboard",
            self::Solicitante => "/requests/mine",
        };
    }
}
