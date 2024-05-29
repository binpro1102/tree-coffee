<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant_image extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'img_path',
        'highlight'
    ];

    protected $primaryKey = "restaurant_img_id";
}
