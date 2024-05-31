<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'img'
    ];
    protected $primaryKey = "category_id";
    protected $table = 'product_category';
}
