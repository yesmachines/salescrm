<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItems;
use App\Models\Orders;

class OrderDeliveries extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'shipping_term',
        'payment_term',
        'advance_received',
        'delivery_time',
        'delivery_target',
        'userid',
        'otp_emails'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_delivery_id', 'id');
    }
    public function setAdvanceReceivedAttribute($value)
    {
        $this->attributes['advance_received'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function setDeliveryTargetAttribute($value)
    {
        $this->attributes['delivery_target'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
}
