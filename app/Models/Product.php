<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BuyingPrice;
use App\Models\OrderItem;

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
    'purchase_currency',
    'purchase_price',
    'image_url',
    'price_valid_from',
    'price_valid_to',
    'product_category',
    'status',
    'currency',
    'price_basis',
    'part_number',
    'is_demo'
  ];
  public function buyingPrice(): HasMany
  {
    return $this->hasMany(BuyingPrice::class, "product_id", "id")->orderBy('id', 'desc');
  }
  public function productPriceHistory()
  {
      return $this->hasMany(ProductPriceHistory::class);
  }


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
  public function setPurchasePriceAttribute($value)
  {
    $b = str_replace(',', '', $value);
    return $this->attributes['purchase_price'] = ($value) ? (float)$b : 0;
  }
  public function prItem()
  {
    return $this->hasMany(PrItem::class, 'product_id', 'id');
  }
  public function orderItem()
  {
    return $this->hasMany(OrderItem::class, 'product_id', 'id');
  }
  public function customPrice(): HasMany
  {
    return $this->hasMany(CustomPrice::class, "product_id", "id");
  }
}
