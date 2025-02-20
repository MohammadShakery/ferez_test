<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name','sort','parent_id','icon','status','cdn_icon'];

    public function parent()
    {
        if($this->parent_id != 0)
        {
            return null;
        }
        return $this->belongsTo(Category::class,'parent_id','id');
    }

    public function children()
    {
        return $this->hasMany(Category::class,'parent_id','id')->with('brands');
    }

    public function brands()
    {
        return $this->hasMany(Brand::class)->where('status',true)->orderByDesc('priority');
    }

    public function newBrands()
    {
        return $this->hasMany(Brand::class)->where('status',true)->orderByDesc('created_at')->limit(10);
    }

    public function popularBrands()
    {
        return $this->hasMany(Brand::class)->where('status',true)->orderByDesc('view')->limit(10);
    }

    public function special_sale()
    {
        return $this->hasMany(specialSale::class)->where('status',true);
    }


}
