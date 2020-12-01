<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update_Expense extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
