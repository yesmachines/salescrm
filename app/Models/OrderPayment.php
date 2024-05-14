<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'section_type',
        'payment_term',
        'expected_date',
        'remarks',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function setExpectedDateAttribute($value)
    {
        $this->attributes['expected_date'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
}
