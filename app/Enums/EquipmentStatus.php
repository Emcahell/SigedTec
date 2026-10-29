<?php

namespace App\Enums;

enum EquipmentStatus: string
{
    case Disponible = 'disponible';
    case Asignado = 'asignado';
    case Mantenimiento = 'mantenimiento';

    /**
     * Human readable label for the equipment status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Disponible => 'Disponible',
            self::Asignado => 'Asignado',
            self::Mantenimiento => 'En Mantenimiento',
        };
    }

    /**
     * Tailwind classes used to badge the status in the UI.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Disponible => 'bg-green-100 text-green-800',
            self::Asignado => 'bg-blue-100 text-blue-800',
            self::Mantenimiento => 'bg-yellow-100 text-yellow-800',
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
