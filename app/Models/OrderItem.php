<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'item_name',
        'quantity',
        'partno',
        'yes_number',
        'currency',
        'total_amount',
        'expected_delivery',
        'remarks',
        'status'
    ];

    public function setExpectedDeliveryAttribute($value)
    {
        $this->attributes['expected_delivery'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
