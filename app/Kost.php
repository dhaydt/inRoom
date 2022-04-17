<?php

namespace App;

use App\Model\Kampus;
use App\Model\Product;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class Kost extends Model
{
    public function rooms()
    {
        return $this->hasMany(Product::class, 'kost_id');
    }

    public function cities()
    {
        return $this->belongsTo(City::class, 'city', 'name');
    }

    public function dis()
    {
        return $this->belongsTo(District::class, 'district', 'name');
    }

    public function kampus()
    {
        return $this->belongsTo(Kampus::class, 'ptn_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'kost_id');
    }
}
