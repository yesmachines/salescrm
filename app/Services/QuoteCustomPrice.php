<?php

namespace App\Services;

use App\Models\QuotationCustomPrice;
use App\Models\QuotationCharge;

class QuoteCustomPrice
{
  /**
  * Create Custom Prices for a Quotation
  *
  * @param object $quotation
  * @param array $userData
  * @return void
  */

  public function updateQuotationCustom($quotation, array $userData)
  {

    $itemIds = $userData['item_id'];
    $quotationId = $userData['quotation_id'] ?? $quotation->id;
    $customPriceData = json_decode($userData['customprice'], true);

    for ($index = 0; $index < count($itemIds); $index++) {

      $attributes = [
        'quotation_id' => $quotationId,
        'product_id' => $itemIds[$index],
      ];


      $values = [
        'selling_price' => $userData['unit_price'][$index],
        'margin_price' => $userData['product_margin'][$index],
        'margin_percent' => $userData['margin_percent'][$index],
        'selling_currency' => $userData['product_currency'][$index],
        'updated_by' => auth()->id(),
      ];


      QuotationCustomPrice::updateOrCreate($attributes, $values);
    }

    foreach ($customPriceData as $row) {
        // Ensure 'product_id' and other necessary data exist
        if (!isset($row['product_id'], $row['id'])) {
            continue; // Skip if required keys are missing
        }

        // Extract the product_id and filter out 'id' and null values
        $productId = $row['product_id'];

        foreach ($row as $key => $value) {
            // Skip irrelevant keys
            if (in_array($key, ['id', 'product_id']) || is_null($value)) {
                continue;
            }

            // Define attributes for updateOrCreate
            $attributes = [
                'quotation_id' => $quotationId,
                'product_id' => $productId, // Use product_id from the current row
            ];

            // Define dynamic values to update
            $values = [
                $key => $value,                // Use $key as the column name
                'updated_by' => auth()->id(),  // Add updated_by for tracking
            ];

            // Perform the database operation
            QuotationCustomPrice::updateOrCreate($attributes, $values);
        }
    }



    QuotationCharge::where('quotation_id', $quotationId)->delete();
    if (isset($userData['charge_name']) && isset($userData['charge_amount'])) {
      $chargeType = $userData['charge_name'];
      $chargeAmount = $userData['charge_amount'];
      $quoteVisible = $userData['is_visible'];
    }

    if (isset($chargeType)) {

      foreach ($chargeType as $index => $charge) {
        if ($chargeAmount[$index] !== null) {
          $shortcodeTitle = strtolower(str_replace(' ', '_', $charge));

          $newQuotationCharge = QuotationCharge::create([
            'quotation_id' => $quotationId,
            'title' => $charge,
            'amount' => $chargeAmount[$index],
            'short_code' => $shortcodeTitle,
            'quote_visible' => $quoteVisible[$index],
          ]);

        }
      }
    }

    return response()->json(['message' => 'Custom prices updated successfully!']);
  }


}
