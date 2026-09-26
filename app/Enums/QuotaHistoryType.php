<?php

namespace App\Enums;

enum QuotaHistoryType: string
{
    case Add = 'add';
    case Deduct = 'deduct';
    case Consume = 'consume';

    public function label(): string
    {
        return match ($this) {
            self::Add => 'Add Quota',
            self::Deduct => 'Deduct Quota',
            self::Consume => 'Consume Quota',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return [
            self::Add->value => self::Add->label(),
            self::Deduct->value => self::Deduct->label(),
            self::Consume->value => self::Consume->label(),
        ];
    }
}
