<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'supplier_id',
        'os_id',
        'pr_for',
        'pr_number',
        'pr_date',
        'pr_type',
        'company_id',
        'total_price',
        'currency',
        'created_by',
        'status'
    ];

    public function setTotalPriceAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['total_price'] = ($value) ? (float)$b : 0;
    }
    public function setPrDateAttribute($value)
    {
        return $this->attributes['pr_date'] = date("Y-m-d", strtotime($value));
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'os_id', 'id');
    }
    public function purchaseItem()
    {
        return $this->hasMany(PrItem::class, 'pr_id', 'id');
    }
    public function purchaseDelivery()
    {
        return $this->hasOne(PrDeliveryTerm::class, 'pr_id', 'id');
    }
    public function purchasePaymentTerm()
    {
        return $this->hasMany(PrPaymentTerm::class, 'pr_id', 'id');
    }
    public function assigned()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'id');
    }
}
