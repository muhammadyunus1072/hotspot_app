<?php

namespace App\Repositories\Transaction;

use App\Models\PaymentMethod;
use App\Repositories\MasterDataRepository;

class PaymentMethodRepository extends MasterDataRepository
{
    protected static function className(): string
    {
        return PaymentMethod::class;
    }

    public static function datatable()
    {
        return PaymentMethod::query();
    }

    public static function search($search)
    {
        $data = PaymentMethod::select(
                'id',
                'name',
                'description',
            )
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })
            ->orderBy('name', 'ASC')
            ->limit(100)
            ->get();

        $response = array();

        foreach ($data as $res) {
            array_push($response, ['id' => $res->id, 'text' => "$res->name", 'description' => "$res->description"]);
        }
        return $response;
    }
}
