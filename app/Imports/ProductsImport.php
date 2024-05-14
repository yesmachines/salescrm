<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Product;
use Carbon\Carbon;

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
    $firstRow = true; // Variable to track the first row

    foreach ($rows as $row) {

      if ($firstRow) {
        $firstRow = false; // Skip the first row
        continue;
      }

      if ($row->filter()->isEmpty()) {
        continue;
      }

      try {
        $product = new Product(); // Create the product object here

        $selling_price = (double) str_replace(['AED', ' ', ','], '', $row[4]);
        $margin_price = (double) str_replace(['AED', ' ', ','], '', $row[5]);

        $selling_price = number_format($selling_price, 2, '.', '');
        $margin_price = number_format($margin_price, 2, '.', '');
        $marginPercent = $row[6] * 100;
        $allowedDiscount = $row[7] * 100;


        $marginPercentFormatted = number_format($marginPercent, 2);
        $allowedDiscountFormatted = number_format($allowedDiscount, 2);



        $product->title = $row[0];
        $product->modelno = $row[1];
        $product->part_number = !empty($row[2]) ? $row[2] : '';
        $product->description = $row[3];
        $product->selling_price = $selling_price;
        $product->margin_price = $margin_price;
        $product->margin_percent = $marginPercentFormatted;
        $product->allowed_discount =  $allowedDiscountFormatted;
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
      } catch (\Exception $e) {
        \Log::error('Error importing product: ' . $e->getMessage());
      }
    }
  }


}
