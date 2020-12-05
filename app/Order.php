<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(Order_Detail::class);
    }

    public function orders_update()
    {
        return $this->hasMany(Update_Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m',
        'deleted_at' => 'datetime:Y-m-d H:m',
    ];


}
