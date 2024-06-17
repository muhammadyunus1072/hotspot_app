<?php

namespace App\Repositories\Transaction;

use App\Models\HotspotMember;
use App\Repositories\MasterDataRepository;

class HotspotMemberRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return HotspotMember::class;
    }

    public static function datatable()
    {
        return HotspotMember::select(
            'hotspot_members.id',
            'users.name as member_name',
            'products.name as product_name',
            'products.price as product_price',
        )
        ->join('users', 'hotspot_members.user_id', '=', 'users.id')
        ->join('products', 'hotspot_members.product_id', '=', 'products.id');
    }

    public static function findWithDetail($id)
    {
        return HotspotMember::select(
            'hotspot_members.id',
            'users.id as member_id',
            'users.name as member_name',
            'products.id as product_id',
            'products.name as product_name',
            'products.price as product_price',
        )
        ->join('users', 'hotspot_members.user_id', '=', 'users.id')
        ->join('products', 'hotspot_members.product_id', '=', 'products.id')
        ->where('hotspot_members.id', $id)
        ->first();
    }

    public static function search($search)
    {
        $data = HotspotMember::select(
                'product_locations.*',
                'products.name',
                'products.unit'
            )
            ->join('products', 'product_locations.product_id', '=', 'products.id')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('products.name', 'LIKE', "%$search%");
                });
            })

            ->limit(100)
            ->get();

        $response = array();

        foreach ($data as $res) {
            array_push($response, ['id' => $res->id, 'text' => "$res->name - $res->price_normal", 'price_minimum' => $res->price_minimum, 'price_normal' => $res->price_normal, 'name' => $res->name, 'unit' => $res->unit]);
        }
        return $response;
    }
}
