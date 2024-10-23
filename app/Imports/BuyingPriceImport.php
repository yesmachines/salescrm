<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Product;
use App\Models\BuyingPrice;
use App\Models\ProductPriceHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BuyingPriceImport implements ToCollection
{
  /**
  * @param Collection $collection
  */

  private $brandId;
  private $currency;

  public function __construct($brandId,$currency)
  {

    $this->brandId = $brandId;
    $this->currency = $currency;

  }


  public function collection(Collection $rows)
  {

    $firstRow = true;
    foreach ($rows as $row) {

      if ($firstRow) {
        $firstRow = false;
        continue;
      }

      if ($row->filter()->isEmpty()) {
        continue;
      }

      try {
        $title = $row[0];
        $modelno = $row[1];
        $grossPrice = (double) str_replace(['AED', ' ', ','], '', $row[2]);
        $discount = $row[3] * 100;
        $discountAmount = (double) str_replace(['AED', ' ', ','], '', $row[4]);
        $buyingPrice = (double) str_replace(['AED', ' ', ','], '', $row[5]);
        $buyingCurrency =  $this->currency;

        $product = Product::where(function ($query) use ($modelno) {
          if (!empty($modelno)) {

            $query->where('modelno', $modelno)
            ->orWhere('part_number', $modelno);
          }
        })
        ->where('title', $title)
        ->where('brand_id', $this->brandId)
        ->where('product_type', 'standard')
        ->where('status', 'active')
        ->first();

        if ($product) {
          $productId = $product->id;

          BuyingPrice::create([
            'product_id' => $productId,
            'gross_price' => $grossPrice,
            'discount' => $discount,
            'discount_amount' => $discountAmount,
            'buying_price' => $buyingPrice,
            'buying_currency' => $buyingCurrency,
            'validity_from' => Carbon::today(),
            'validity_to' => Carbon::today()->addMonths(12),
            'status' => "1",
          ]);
        } else {
          \Log::warning('Product with modelno ' . $modelno . ' not found.');
        }
      } catch (\Exception $e) {
        \Log::error('Error importing product: ' . $e->getMessage());
      }
    }
  }
}
