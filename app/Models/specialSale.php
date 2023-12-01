<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class specialSale extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','price','percent','contact','category_id'];

    public function images()
    {
        return $this->hasMany(specialSaleImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
