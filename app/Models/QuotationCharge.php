<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationCharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'quotation_id',
        'title',
        'short_code',
        'amount',
        'sort_order',
        'quote_visible',
    ];
}
