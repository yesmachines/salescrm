<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrPaymentTerm extends Model
{
    use HasFactory;
    protected $fillable = [
        'pr_id',
        'payment_term',
        'remarks',
        'status'
    ];
    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }
}
