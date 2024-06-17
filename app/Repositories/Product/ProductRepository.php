<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Models\MonthlyHotspot;
use App\Helpers\NumberFormatter;
use App\Repositories\MasterDataRepository;

class ProductRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return Product::class;
    }

    public static function datatable()
    {
        return Product::query();
    }

    public static function search_monthly_hotspot($search)
    {
        $data = Product::select(
                'id',
                'name',
                'price'
            )
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })
            ->whereNotNull('remarks_id')
            ->where('remarks_type', MonthlyHotspot::class)
            ->orderBy('name', 'ASC')
            ->limit(100)
            ->get();

        $response = array();

        foreach ($data as $res) {
            array_push($response, ['id' => $res->id, 'text' => "$res->name - Rp ".NumberFormatter::format($res->price)]);
        }
        return $response;
    }
    public static function search_product($search)
    {
        $data = Product::select(
                'id',
                'name',
                'price'
            )
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })
            ->whereNull('remarks_id')
            ->whereNull('remarks_type')
            ->orderBy('name', 'ASC')
            ->limit(100)
            ->get();

        $response = array();

        foreach ($data as $res) {
            array_push($response, ['id' => $res->id, 'text' => "$res->name - Rp ".NumberFormatter::format($res->price)]);
        }
        return $response;
    }
}
