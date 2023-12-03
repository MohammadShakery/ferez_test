<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = ['name','category_price_id','price','unit','type'];

    public function category()
    {
        return $this->belongsTo(categoryPrice::class,'category_price_id','id');
    }
}
