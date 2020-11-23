<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(Order_Detail::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m',
    ];


}
