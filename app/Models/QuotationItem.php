<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
  use HasFactory;
  protected $fillable = [
    'quotation_id',
    'item_id',
    'description',
    'unit_price',
    'quantity',
    'subtotal',
    'discount',
    'total_after_discount',
    'brand_id',
    'margin_price',
    'discount_status',
    'unit_margin'
  ];
  public function product()
  {
    return $this->belongsTo(Product::class, 'item_id', 'id');
  }

  public function supplier()
  {
    return $this->belongsTo(Supplier::class, 'brand_id', 'id');
  }
  public function setUnitPriceAttribute($value)
  {
    $b = str_replace(',', '', $value);
    return $this->attributes['unit_price'] = ($value) ? (float)$b : 0;
  }
  public function setSubtotalAttribute($value)
  {
    $b = str_replace(',', '', $value);
    return $this->attributes['subtotal'] = ($value) ? (float)$b : 0;
  }
  public function setTotalAfterDiscountAttribute($value)
  {
    $b = str_replace(',', '', $value);
    return $this->attributes['total_after_discount'] = ($value) ? (float)$b : 0;
  }
  public function setMarginPriceAttribute($value)
  {
    $b = str_replace(',', '', $value);
    return $this->attributes['margin_price'] = ($value) ? (float)$b : 0;
  }
  public function productPriceHistory()
  {
      return $this->hasOne(ProductPriceHistory::class, 'product_id', 'item_id');
  }
  public function customPrice()
     {
         return $this->belongsTo(CustomPrice::class, 'custom_price_id');
     }
     // In QuotationItem.php model
   public function quotationCustomPrice()
   {
       return $this->belongsTo(QuotationCustomPrice::class, 'item_id', 'product_id');
   }
  public function quoteCustomPrice($quotationId)
   {
       return $this->hasOne(QuotationCustomPrice::class, 'product_id', 'item_id')
                   ->where('quotation_id', $quotationId);
   }

}
