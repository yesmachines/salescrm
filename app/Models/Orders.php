<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Company;
use App\Models\OrderDeliveries;
use App\Models\OrderHistories;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'customer_id',
        'yespo_no',
        'po_number',
        'po_date',
        'po_received',
        'otp_code',
        'otp_expire_date_time',
        'short_link_code',
        'status',

    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function orderDelivery()
    {
        return $this->hasOne(OrderDeliveries::class, 'order_id', 'id');
    }
    public function orderHistory()
    {
        return $this->hasMany(OrderHistories::class, 'order_id', 'id');
    }
    public function setPoDateAttribute($value)
    {
        $this->attributes['po_date'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function setPoReceivedAttribute($value)
    {
        $this->attributes['po_received'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function orderServiceRequest()
    {
        return $this->hasOne(OrderServiceRequest::class, 'order_id', 'id');
    }
}
