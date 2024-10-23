<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrDeliveryTerm extends Model
{
    use HasFactory;
    protected $fillable = [
        'pr_id',
        'country_id',
        'supplier_email',
        'supplier_contact',
        'delivery_term',
        'shipping_mode',
        'availability',
        'warranty',
        'remarks'
    ];
    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }
}
