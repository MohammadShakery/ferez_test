<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = ['title','cdn_image','image','requirement_category_id','description','contact'];

    public function category()
    {
        return $this->belongsTo(requirementCategory::class,"requirement_category_id",'id');
    }
}
