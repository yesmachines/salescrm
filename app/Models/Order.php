<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Company;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\OrderClient;
use App\Models\OrderSupplier;
use App\Models\OrderPayment;
use App\Models\OrderCharge;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'customer_id',
        'order_for',
        'quotation_id',
        'os_number',
        'os_date',
        'po_number',
        'po_date',
        'po_received',
        'currency',
        'selling_price',
        'buying_price',
        'projected_margin',
        'actual_margin',
        'material_status',
        'material_details',
        'created_by',
        'status'
    ];

    public function setOsDateAttribute($value)
    {
        $this->attributes['os_date'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function setPoDateAttribute($value)
    {
        $this->attributes['po_date'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function setPoReceivedAttribute($value)
    {
        $this->attributes['po_received'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // public function orderHistory()
    // {
    //     return $this->hasMany(OrderHistory::class, 'order_id', 'id');
    // }
    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function orderSupplier()
    {
        return $this->hasMany(OrderSupplier::class, 'order_id', 'id');
    }
    public function orderClient()
    {
        return $this->hasOne(OrderClient::class, 'order_id', 'id');
    }
    public function orderPayment()
    {
        return $this->hasMany(OrderPayment::class, 'order_id', 'id');
    }
    public function orderCharge()
    {
        return $this->hasMany(OrderCharge::class, 'order_id', 'id');
    }
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'created_by', 'id');
    // }
    public function assigned()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'id');
    }
    public function getProductNamesAttribute()
    {
        return $this->orderItem ? $this->orderItem->pluck('item_name')->implode(' , ') : '';
    }
    public function getSupplierBrandsAttribute()
    {
        return $this->orderSupplier->map(function ($orderSupplier) {
            return $orderSupplier->supplier ? $orderSupplier->supplier->brand : 'Unknown';
        })->implode(', ');
    }
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
    public function getProductCategoriesAttribute()
    {
        $categories = $this->orderItem->map(function ($item) {
            return optional($item->product)->product_category;
        });
        return $categories->filter()->unique()->implode(', ');
    }
    public function purchaseRequisition()
    {
        return $this->hasOne(PurchaseRequisition::class, 'os_id', 'id');
    }
}
