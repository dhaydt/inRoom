<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserPoin extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'persen',
        'shop',
        'poin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
