<?php

namespace App\Repositories\Product;

use App\Models\MonthlyHotspot;
use App\Repositories\MasterDataRepository;

class MonthlyHotspotRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return MonthlyHotspot::class;
    }

    public static function datatable()
    {
        return MonthlyHotspot::query();
    }
}
