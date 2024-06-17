<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyHotspot extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'price_before_discount',
    ];


    protected static function onBoot()
    {
        self::created(function ($model) {
            $product = new Product();
            $product->name = $model->name;
            $product->description = $model->description;
            $product->price = $model->price;
            $product->price_before_discount = $model->price_before_discount;
            $product->remarks_id = $model->id;
            $product->remarks_type = self::class;
            $product->save();
        });
        self::updated(function ($model) {
            $product = $model->product;
            $product->name = $model->name;
            $product->description = $model->description;
            $product->price = $model->price;
            $product->price_before_discount = $model->price_before_discount;
            $product->save();
        });
        self::deleted(function ($model) {
            $product = $model->product;
            $product->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id', 'remarks_id')->where('remarks_type', self::class);
    }
}
