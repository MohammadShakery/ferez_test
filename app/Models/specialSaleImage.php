<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class specialSaleImage extends Model
{
    use HasFactory;

    protected $fillable = ['special_sale_id','src','cdn_image'];

    public function special_sale()
    {
        return $this->belongsTo(specialSale::class);
    }
}
