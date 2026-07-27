<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $guarded = [];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }
}