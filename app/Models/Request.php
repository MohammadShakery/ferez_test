<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = ['type','data','checked','user_id'];

    protected $appends = ['information'];
    protected $hidden = ['data'];

    public function getInformationAttribute()
    {
        return json_decode($this->data);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
