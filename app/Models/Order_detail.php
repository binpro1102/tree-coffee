<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'order_id',
        'price',
        'quantity',
        'discount',
        'is_delete'
    ];
    protected $primaryKey = "order_detail_id";
}
