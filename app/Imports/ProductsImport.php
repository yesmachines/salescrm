<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use App\Models\BuyingPrice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProductsImport implements ToCollection
{
  private $divisionId;
  private $brandId;
  private $managerId;

  /**
  * Constructor to accept divisionId, brandId, and managerId
  */
  public function __construct($divisionId, $brandId, $managerId)
  {
    $this->divisionId = $divisionId;
    $this->brandId = $brandId;
    $this->managerId = $managerId;
  }

  /**
  * @param Collection $collection
  */
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
        if (!empty($row[1]) || !empty($row[2])) {

          $productQuery = Product::query();

          $productQuery->where('brand_id', $this->brandId);
          if (!empty($row[1])) {
            $productQuery->where('modelno', $row[1]);
          }

          if (!empty($row[2])) {
            $productQuery->orWhere('part_number', $row[2]);
          }

          $product = $productQuery->first();

        } else {
          $product = null;
        }

        $selling_price = (double) str_replace(['AED', ' ', ','], '', $row[4]);
        $margin_price = (double) str_replace(['AED', ' ', ','], '', $row[5]);


        $selling_price = (int) round($selling_price);
        $margin_price = (int) round($margin_price);


        $marginPercent = $row[6] * 100;
        $allowedDiscount = $row[7] * 100;

        $marginPercentFormatted = number_format($marginPercent, 2);
        $allowedDiscountFormatted = number_format($allowedDiscount, 2);

        if ($product) {

          $product->title = $row[0];
          $product->description = $row[3];
          $product->selling_price = $selling_price;
          $product->margin_price = $margin_price;
          $product->margin_percent = $marginPercentFormatted;
          $product->allowed_discount = $allowedDiscountFormatted;
          $product->product_category = $row[8];
          $product->price_valid_from = Carbon::today();
          $product->price_valid_to = Carbon::today()->addMonths(12);
          $product->price_basis = $row[9];
          $product->currency = $row[10];
          $product->freeze_discount = '1';
          $product->save();
        } else {

          $product = new Product();
          $product->title = $row[0];
          $product->modelno = $row[1];
          $product->part_number = !empty($row[2]) ? $row[2] : '';
          $product->description = $row[3];
          $product->selling_price = $selling_price;
          $product->margin_price = $margin_price;
          $product->margin_percent = $marginPercentFormatted;
          $product->allowed_discount = $allowedDiscountFormatted;
          $product->product_category = $row[8];
          $product->status = 'active';
          $product->price_valid_from = Carbon::today();
          $product->price_valid_to = Carbon::today()->addMonths(12);
          $product->brand_id = $this->brandId;
          $product->division_id = $this->divisionId;
          $product->manager_id = $this->managerId;
          $product->product_type = 'standard';
          $product->price_basis = $row[9];
          $product->currency = $row[10];
          $product->freeze_discount = '1';
          $product->save();
        }
        $buyingPrice = BuyingPrice::where('product_id', $product->id)->first();

        $discount = (int)($row[12] * 100);

        if ($buyingPrice) {

          $buyingPrice->gross_price = $row[11];
          $buyingPrice->discount =  $discount ;
          $buyingPrice->discount_amount = $row[13];
          $buyingPrice->buying_price = $row[14];
          $buyingPrice->buying_currency = $row[15];
          $buyingPrice->validity_from = Carbon::today();
          $buyingPrice->validity_to = Carbon::today()->addMonths(12);
          $buyingPrice->status = '1';

          $buyingPrice->save();
        } else {

          BuyingPrice::create([
            'product_id' => $product->id,
            'gross_price' => $row[11],
            'discount' => $discount ,
            'discount_amount' => $row[13],
            'buying_price' => $row[14],
            'buying_currency' => $row[15],
            'validity_from' => Carbon::today(),
            'validity_to' => Carbon::today()->addMonths(12),
            'status' => '1',
          ]);

        }
        ProductPriceHistory::create([
          'product_id' => $product->id,
          'selling_price' => $selling_price,
          'margin_price' => $margin_price,
          'price_valid_from' => Carbon::today(),
          'price_valid_to' => Carbon::today()->addMonths(12),
          'price_basis' => $row[9],
          'margin_percent' => $marginPercentFormatted,
          'currency' => $row[10],
          'edited_by' => Auth::id()
        ]);
      } catch (\Exception $e) {
        \Log::error('Error importing product: ' . $e->getMessage());
      }
    }
  }
}
