<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update_Order extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function update_details()
    {
        return $this->hasMany(Update_Order_Details::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m',
    ];
}
