<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Attending = 'attending';
    case NotAttending = 'not_attending';
    case Ucertain = 'ucertain';

    public function label(): string
    {
        return match ($this) {
            self::Attending => 'Attending',
            self::NotAttending => 'Not Attending',
            self::Ucertain => 'Uncertain',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return [
            self::Attending->value => self::Attending->label(),
            self::NotAttending->value => self::NotAttending->label(),
            self::Ucertain->value => self::Ucertain->label(),
        ];
    }
}
