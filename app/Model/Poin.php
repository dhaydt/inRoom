<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Poin extends Model
{
    protected $fillable = ['title', 'transaction', 'status', 'persen'];
}
