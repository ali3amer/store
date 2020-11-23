<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    protected $casts = ['purchase_price' => 'integer', 'stock' => 'integer'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function details()
    {
        return $this->hasMany(Product_Detail::class);
    }

    public function order_details()
    {
        return $this->hasMany(Order_Detail::class);
    }
}
