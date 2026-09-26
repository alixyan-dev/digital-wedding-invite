<?php

namespace App\Enums;

enum InvitationEventType: string
{
    case Ceremony = 'ceremony';
    case Reception = 'reception';

    public function label(): string
    {
        return match ($this) {
            self::Ceremony => 'Ceremony',
            self::Reception => 'Reception',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return [
            self::Ceremony->value => self::Ceremony->label(),
            self::Reception->value => self::Reception->label(),
        ];
    }
}
