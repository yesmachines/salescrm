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
    public function collection(Collection $collection)
    {
        $collection = $collection->slice(1);


        foreach ($collection as $row) {

           $selling_price = str_replace(['AED', ' ', ','], '', $row[3]);
            $margin_price = str_replace(['AED', ' ', ','], '', $row[4]);
            $margin_percent = $row[5];

            $product = new Product();
            $product->title = $row[0];
            $product->modelno = $row[1];
            $product->description = $row[2];
            $product->selling_price = (double)$selling_price;
            $product->margin_price = (double)$margin_price;
            $product->margin_percent = (double)$margin_percent;
            $product->allowed_discount = $row[6];
            $product->product_category = $row[7];
            $product->status = 'active';
            $product->price_valid_from = Carbon::today();
            $product->price_valid_to = Carbon::today()->addMonths(12);
            $product->brand_id = $this->brandId;
            $product->division_id = $this->divisionId;
            $product->manager_id = $this->managerId;
            $product->product_type = 'standard';
            $product->price_basis = 'yes_warehouse';
            $product->currency = 'aed';
            $product->freeze_discount='1';

            $product->save();
        }
    }
}
