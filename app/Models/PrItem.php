<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pr_id',
        'product_id',
        'partno',
        'item_description',
        'unit_price',
        'quantity',
        'discount',
        'total_amount',
        'currency',
        'yes_number',
        'remarks'
    ];
    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function setTotalAmountAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['total_amount'] = ($value) ? (float)$b : 0;
    }
    public function setUnitPriceAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['unit_price'] = ($value) ? (float)$b : 0;
    }
}
