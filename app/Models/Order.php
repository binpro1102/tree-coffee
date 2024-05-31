<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'restaurant_id',
        'order_date',
        'total_price',
        'shipping_address',
        'note',
        'total_discount',
        'sub_total',
        'status'
    ];
    protected $primaryKey = "order_id";
}
