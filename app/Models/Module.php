<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['name','start_color','end_color','icon','route'];


    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
