<?php

namespace App\Enums;

enum RequestStatus: string
{
    case Pendiente = 'pendiente';
    case Aprobada = 'aprobada';
    case Rechazada = 'rechazada';

    /**
     * Human readable label for the request status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente',
            self::Aprobada => 'Aprobada',
            self::Rechazada => 'Rechazada',
        };
    }

    /**
     * Plain text colour used to highlight the status (no badges).
     */
    public function textClass(): string
    {
        return match ($this) {
            self::Pendiente => 'text-blue-700',
            self::Aprobada => 'text-green-700',
            self::Rechazada => 'text-red-700',
        };
    }

    /**
     * Every status as a value => label map, handy for selects.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }
}
