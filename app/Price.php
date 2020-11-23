<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m',
    ];
}
