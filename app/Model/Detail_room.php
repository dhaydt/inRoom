<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Detail_room extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'room_id', 'room_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
