<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoryPrice extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
