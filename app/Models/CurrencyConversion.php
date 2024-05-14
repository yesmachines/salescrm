<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyConversion extends Model
{
    use HasFactory;
    protected $table = 'currency_conversion';
    protected $fillable = [
        'currency',
        'standard_rate',
        'date_on',
        'status',

    ];
}
