<?php

namespace App\Exports;

use App\Models\QuotationItem;
use App\Models\ProductPriceHistory;
use App\Models\CustomPrice;
use App\Models\QuotationCustomPrice;
use App\Models\CustomField;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomPriceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $quotationId;
    protected $customHeadings;

    public function __construct($quotationId, $customHeadings)
    {
        $this->quotationId = $quotationId;
        $this->customHeadings = $customHeadings;
    }

    public function collection()
    {
        return QuotationItem::with([
            'product',
            'product.buyingPrice',
            'productPriceHistory'
        ])
        ->where('quotation_id', $this->quotationId)
        ->get();
    }

    public function headings(): array
    {
        $fixedHeadings = [
            'Part Number/Model No',
            'Description',
            'Gross Price',
            'Disc %',
            'Disc Amt',
            'Qty',
            'Net Price',
        ];

        foreach ($this->customHeadings as $customField) {
            $fixedHeadings[] = $customField->field_name;
        }

        $remainingHeadings = [
            '% Margin',
            'Margin',
        ];

        return array_merge($fixedHeadings, $remainingHeadings);
    }

    public function map($row): array
  {
      $product = $row->product;
      $buyingPrice = $product->buyingPrice->first();

      $grossPrice = $buyingPrice && $buyingPrice->gross_price ? $buyingPrice->gross_price : 0;
      $discountPercentage = $buyingPrice && $buyingPrice->discount ? $buyingPrice->discount : 0;

      $discountAmount = $grossPrice * ($discountPercentage / 100);

      $mappedData = [
          $product->part_number ?? $product->modelno ?? 'N/A',
          $product->description ?? 'N/A',
          number_format($grossPrice, 2),
          number_format($discountPercentage, 2),
          number_format($discountAmount, 2),
          number_format($row->quantity ?? 0, 2),
          number_format($buyingPrice && $buyingPrice->buying_price ? $buyingPrice->buying_price : 0, 2),
      ];

      $customFieldValues = [];
      $shortCodes = [];
      foreach ($this->customHeadings as $customField) {
          $shortCodes[] = trim($customField->short_code);
      }

      $customPriceQuote = QuotationCustomPrice::where('quotation_id', $this->quotationId)
          ->where('product_id', $row->item_id)
          ->first(); 

      if ($customPriceQuote) {

          $attributes = $customPriceQuote->getAttributes();

          foreach ($shortCodes as $shortCode) {
              $shortCode = trim($shortCode);

              if (array_key_exists($shortCode, $attributes)) {

                  $fieldName = CustomField::where('short_code', $shortCode)->first();

                  if ($fieldName) {
                      $fieldNameValue = $fieldName->field_name;
                      $customFieldValues[$fieldNameValue] = $attributes[$shortCode];

                  }
              } else {

                  $fieldName = CustomField::where('short_code', $shortCode)->first();
                  if ($fieldName) {
                      $fieldNameValue = $fieldName->field_name;
                      $customFieldValues[$fieldNameValue] = null;
                  }
              }
          }
      }


      foreach ($this->customHeadings as $customField) {
          $fieldName = $customField->field_name;
          $mappedData[] = $customFieldValues[$fieldName] ?? 'N/A';
      }


      $mappedData[] = number_format($row->productPriceHistory->margin_percent ?? 0, 2);
      $mappedData[] = number_format($row->productPriceHistory->margin_price ?? 0, 2);

      return $mappedData;
  }



}
