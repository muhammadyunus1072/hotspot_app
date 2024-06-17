<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'qty',
    ];

    protected static function onBoot()
    {
        self::creating(function ($model) {
            $product = $model->product;
            $model->product_name = $product->name;
            $model->product_description = $product->description;
            $model->product_price = $product->price;
            $model->product_price_before_discount = $product->price_before_discount;
            $model->product_remarks_id = $product->remarks_id;
            $model->product_remarks_type = $product->remarks_type;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
