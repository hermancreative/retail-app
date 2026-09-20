<?php

namespace App\Domain\MasterData\Enums;

enum CustomerType: string
{
    case Retail = 'retail';
    case Business = 'business';

    public function label(): string
    {
        return match ($this) {
            self::Retail => 'Retail',
            self::Business => 'Business',
        };
    }
}
