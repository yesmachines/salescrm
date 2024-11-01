<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyingPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'gross_price',
        'discount',
        'discount_amount',
        'buying_price',
        'buying_currency',
        'validity_from',
        'validity_to',
    ];

    public function setGrossPriceAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['gross_price'] = ($value) ? (float)$b : 0;
    }
    public function setDiscountAmountAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['discount_amount'] = ($value) ? (float)$b : 0;
    }
    public function setBuyingPriceAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['buying_price'] = ($value) ? (float)$b : 0;
    }
    public function setValidityFromAttribute($value)
    {
        return $this->attributes['validity_from'] = ($value) ? date("Y-m-d", strtotime($value)) : 0;
    }
    public function setValidityToAttribute($value)
    {
        return $this->attributes['validity_to'] = ($value) ? date("Y-m-d", strtotime($value)) : 0;
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
