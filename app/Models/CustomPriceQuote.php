<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPriceQuote extends Model
{
    use HasFactory;
      protected $guarded = [];
      public function customPrice()
 {
     return $this->belongsTo(CustomPrice::class, 'custom_price_id');
 }

}
