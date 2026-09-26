<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Customer = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Customer => 'Customer',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return [
            self::Admin->value => self::Admin->label(),
            self::Customer->value => self::Customer->label(),
        ];
    }
}
