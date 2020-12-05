<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update_Order_Details extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m',
    ];

    public function update_order()
    {
        return $this->belongsTo(Update_Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
