<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_method extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'is_delete'
    ];

    protected $primaryKey = "payment_method_id";
}
