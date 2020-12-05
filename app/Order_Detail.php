<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_Detail extends Model
{
    protected $guarded = [];


    protected $casts = ['purchase_price' => 'integer', 'quantity' => 'integer', 'created_at' => 'datetime:Y-m-d H:m',];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
