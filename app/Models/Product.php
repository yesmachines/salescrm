<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use HasFactory;
  use SoftDeletes;
  protected $fillable = [
    'title',
    'description',
    'modelno',
    'brand_id',
    'division_id',
    'product_type',
    'manager_id',
    'selling_price',
    'margin_percent',
    'margin_price',
    'allowed_discount',
    'freeze_discount',
    'image_url',
    'price_valid_from',
    'price_valid_to',
    'product_category',
    'status',
    'currency',
    'price_basis',
    'part_number'

  ];
  public function supplier()
  {
    return $this->belongsTo(Supplier::class, 'brand_id', 'id');
  }
  public function paymentTerm()
  {
    return $this->belongsTo(PaymentTerm::class, 'payment_term', 'id');
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
}
