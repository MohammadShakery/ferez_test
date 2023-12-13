<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name','image','category_id','view','tell','description','priority','address','cdn_image'
    ,'site','instagram','email','whatsapp','linkedin','telegram','status'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brandCategory()
    {
        return $this->hasMany(brandCategory::class)->with('products');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->where('status',true)->orderByDesc('created_at');
    }

    public function network()
    {
        return $this->belongsToMany(Network::class,'brand_network');
    }


}
