<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'price_basis',
        'delivery_term',
        'promised_delivery',
        'targeted_delivery',
        'installation_training',
        'service_expert',
        'estimated_installation',
        'delivery_address',
        'contact_person',
        'contact_email',
        'contact_phone',
        'remarks',
        'otp_code',
        'otp_expire_date_time',
        'short_link_code',
        'otp_emails',
        'is_demo',
        'demo_by'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function setTargetedDeliveryAttribute($value)
    {
        $this->attributes['targeted_delivery'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function demoby()
    {
        return $this->belongsTo(User::class, 'demo_by', 'id');
    }
}
