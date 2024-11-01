<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'pr_id',
        'title',
        'currency',
        'considered'
    ];
    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }
}
