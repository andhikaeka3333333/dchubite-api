<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['order_code', 'status', 'total_price', 'order_date'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
