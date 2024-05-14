<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Supplier;

class OrderSupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'supplier_id',
        'country_id',
        'supplier_name',
        'price_basis',
        'delivery_term',
        'remarks'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
