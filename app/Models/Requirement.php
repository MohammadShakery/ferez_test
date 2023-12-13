<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = ['title','cdn_image','image','requirement_category_id','description','contact','status','user_id'];

    public function category()
    {
        return $this->belongsTo(requirementCategory::class,"requirement_category_id",'id');
    }


    protected $appends = ['time'];

    public function getTimeAttribute()
    {
        return Jalalian::forge($this->created_at)->ago();
    }

}
