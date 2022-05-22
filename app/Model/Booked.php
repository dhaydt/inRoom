<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Booked extends Model
{
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(function (Builder $builder) {
            $builder->with('customer');
        });
    }
}
