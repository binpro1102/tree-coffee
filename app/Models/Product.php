<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'img',
        'code',
        'unit_price',
        'is_delete'
    ];
    protected $primaryKey = "product_id";
}
