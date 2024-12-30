<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
  use HasFactory;
  protected $fillable = [
    'product_id',
    'selling_price',
    'margin_price',
    'edited_by',
    'currency',
    'price_basis',
    'margin_percent',
    'price_valid_from',
    'price_valid_to',
  ];
  public function user()
  {
    return $this->belongsTo(User::class, 'edited_by', 'id');
  }
  public function setSellingPriceAttribute($value)
  {
    $b = str_replace(',', '', $value);
    return $this->attributes['selling_price'] = ($value) ? (float)$b : 0;
  }
  public function setMarginPriceAttribute($value)
  {
    $b = str_replace(',', '', $value);
    return $this->attributes['margin_price'] = ($value) ? (float)$b : 0;
  }
  public function productCustomPrice()
  {
      return $this->hasOne(CustomPrice::class, 'product_id', 'id');
  }


}
