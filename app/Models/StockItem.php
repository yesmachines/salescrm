<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'stock_id',
        'item_id',
        'item_name',
        'unit_price',
        'quantity',
        'partno',
        'yes_number',
        'currency',
        'total_amount',
        'expected_delivery',
        'remarks',
        'status'
    ];

    public function setTotalAmountAttribute($value)
    {
        $b = str_replace(',', '', $value);
        return $this->attributes['total_amount'] = ($value) ? (float)$b : 0;
    }

    public function setExpectedDeliveryAttribute($value)
    {
        $this->attributes['expected_delivery'] = ($value) ? date("Y-m-d", strtotime($value)) : null;
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id', 'id');
    }
}
