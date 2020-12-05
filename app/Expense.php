<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses_update()
    {
        return $this->hasMany(Update_Expense::class);
    }
}
