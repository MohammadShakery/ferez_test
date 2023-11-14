<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class specialSale extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','price','percent','contact'];

    public function images()
    {
        return $this->hasMany(specialSaleImage::class);
    }
}
