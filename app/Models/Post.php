<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title','file','file_cdn','brand_id','description','view','type'];

    private $request;


    public function likes()
    {
        return $this->belongsToMany(User::class);
    }

    protected $appends = ['count'];

    public function getcountAttribute()
    {
        return $this->belongsToMany(User::class)->count();
    }

    public function is_liked($request)
    {
        if($request->hasHeader('user_id'))
        {
            return $this->belongsToMany(User::class)->where('user_id',$request->header('user_id'))->exists();
        }
        return false;
    }
}
