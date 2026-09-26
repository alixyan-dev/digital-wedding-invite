<?php

namespace App\Enums;

enum GuestSendStatus: string
{
    case Unsent = 'unsent';
    case Sent = 'sent';


    public function label(): string
    {
        return match ($this) {
            self::Unsent => 'Unsent',
            self::Sent => 'Sent',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return [
            self::Unsent->value => self::Unsent->label(),
            self::Sent->value => self::Sent->label(),
        ];
    }
}
