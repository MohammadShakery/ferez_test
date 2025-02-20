<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name','multidimensional_view','brand_category_id','description','link','price','off'];

    public function brand_category()
    {
        return $this->belongsTo(brandCategory::class);
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }



    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
