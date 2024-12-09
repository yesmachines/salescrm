<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $guarded = [];
    // public function user()
    // {
    //     return $this->belongsTo(Employee::class, 'assigned_to', 'id');
    // }
    public function assigned()
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'id');
    }

    public function stockSupplier()
    {
        return $this->hasMany(StockSupplier::class, 'stock_id', 'id');
    }

    public function stockItem()
    {
        return $this->hasMany(StockItem::class, 'stock_id', 'id');
    }

    public function getStockBrandAttribute()
    {
        return $this->stockSupplier()->get()->map(function ($stockSupplier) {
            return $stockSupplier->supplier ? $stockSupplier->supplier->brand : 'Unknown';
        })->implode(', ');
    }

    // public function getStockProductAttribute()
    // {
    //     return $this->stockItem ? $this->stockItem->pluck('item_name')->implode(', ') : '';
    // }


    public function stockPayment()
    {
        return $this->hasMany(StockPayment::class, 'stock_id', 'id');
    }
    public function stockCharge()
    {
        return $this->hasMany(StockCharge::class, 'stock_id', 'id');
    }

    public function purchaseRequisition()
    {
        return $this->hasOne(PurchaseRequisition::class, 'os_id', 'id');
    }
}
