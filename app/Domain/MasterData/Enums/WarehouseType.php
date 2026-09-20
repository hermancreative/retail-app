<?php

namespace App\Domain\MasterData\Enums;

enum WarehouseType: string
{
    case Store = 'store';
    case CentralWarehouse = 'central_warehouse';

    public function label(): string
    {
        return match ($this) {
            self::Store => 'Store',
            self::CentralWarehouse => 'Central Warehouse',
        };
    }
}
