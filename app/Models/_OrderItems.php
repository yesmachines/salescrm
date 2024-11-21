<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDeliveries;

class OrderItems extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_delivery_id',
        'item',
        'partno',
        'quantity',
        'delivered',
        'remarks',
        'status'
    ];

    public function setDeliveredAttribute($value)
    {
        $this->attributes['delivered'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function orderdelivery()
    {
        return $this->belongsTo(OrderDeliveries::class);
    }
}
