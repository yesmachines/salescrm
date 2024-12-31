<?php

namespace App\Services;

use App\Models\QuotationCustomPrice;
use App\Models\QuotationCharge;
use App\Models\BuyingPrice;

class QuoteCustomPrice
{
  /**
  * Create Custom Prices for a Quotation
  *
  * @param object $quotation
  * @param array $userData
  * @return void
  */

  // public function updateQuotationCustom($quotation, array $userData)
  // {
  //
  //   $itemIds = $userData['item_id'];
  //   $quotationId = $userData['quotation_id'] ?? $quotation->id;
  //   $customPriceData = json_decode($userData['customprice'], true);
  //
  //   for ($index = 0; $index < count($itemIds); $index++) {
  //
  //     $attributes = [
  //       'quotation_id' => $quotationId,
  //       'product_id' => $itemIds[$index],
  //     ];
  //
  //
  //     $values = [
  //       'selling_price' => $userData['unit_price'][$index],
  //       'margin_price' => $userData['product_margin'][$index],
  //       'margin_percent' => $userData['margin_percent'][$index],
  //       'selling_currency' => $userData['product_currency'][$index],
  //       'updated_by' => auth()->id(),
  //     ];
  //
  //
  //     QuotationCustomPrice::updateOrCreate($attributes, $values);
  //   }
  //
  //   foreach ($customPriceData as $row) {
  //
  //       if (!isset($row['product_id'], $row['id'])) {
  //           continue;
  //       }
  //
  //
  //       $productId = $row['product_id'];
  //       $buyingPriceLatest = BuyingPrice::where('product_id', $productId)->orderBy('created_at', 'desc')->first();
  //
  //       foreach ($row as $key => $value) {
  //
  //           if (in_array($key, ['id', 'product_id']) || is_null($value)) {
  //               continue;
  //           }
  //
  //
  //           $attributes = [
  //               'quotation_id' => $quotationId,
  //               'product_id' => $productId,
  //           ];
  //
  //
  //           $values = [
  //               $key => $value,
  //               'updated_by' => auth()->id(),
  //           ];
  //
  //
  //           QuotationCustomPrice::updateOrCreate($attributes, $values);
  //       }
  //   }
  //
  //
  //
  //   QuotationCharge::where('quotation_id', $quotationId)->delete();
  //   if (isset($userData['charge_name']) && isset($userData['charge_amount'])) {
  //     $chargeType = $userData['charge_name'];
  //     $chargeAmount = $userData['charge_amount'];
  //     $quoteVisible = $userData['is_visible'];
  //   }
  //
  //   if (isset($chargeType)) {
  //
  //     foreach ($chargeType as $index => $charge) {
  //       if ($chargeAmount[$index] !== null) {
  //         $shortcodeTitle = strtolower(str_replace(' ', '_', $charge));
  //
  //         $newQuotationCharge = QuotationCharge::create([
  //           'quotation_id' => $quotationId,
  //           'title' => $charge,
  //           'amount' => $chargeAmount[$index],
  //           'short_code' => $shortcodeTitle,
  //           'quote_visible' => $quoteVisible[$index],
  //         ]);
  //
  //       }
  //     }
  //   }
  //
  //   return response()->json(['message' => 'Custom prices updated successfully!']);
  // }

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


          $buyingPriceLatest = BuyingPrice::where('product_id', $itemIds[$index])
              ->orderBy('created_at', 'desc')
              ->first();

          if ($buyingPriceLatest) {
              $values['gross_price'] = $userData['gross_price'][$index] ?? $buyingPriceLatest->gross_price;
              $values['discount'] = $userData['discount'][$index] ?? $buyingPriceLatest->discount;
              $values['discount_amount'] = $userData['discount_amount'][$index] ?? $buyingPriceLatest->discount_amount;
              $values['buying_price'] = $userData['buying_price'][$index] ?? $buyingPriceLatest->buying_price;
          }

          QuotationCustomPrice::updateOrCreate($attributes, $values);
      }

      foreach ($customPriceData as $row) {
          if (!isset($row['product_id'], $row['id'])) {
              continue;
          }

          $productId = $row['product_id'];
          $buyingPriceLatest = BuyingPrice::where('product_id', $productId)->orderBy('created_at', 'desc')->first();

          foreach ($row as $key => $value) {
              if (in_array($key, ['id', 'product_id']) || is_null($value)) {
                  continue;
              }

              $attributes = [
                  'quotation_id' => $quotationId,
                  'product_id' => $productId,
              ];

              $values = [
                  $key => $value,
                  'updated_by' => auth()->id(),
              ];


              if ($buyingPriceLatest) {
                  $values['gross_price'] = $row['gross_price'] ?? $buyingPriceLatest->gross_price;
                  $values['discount'] = $row['discount'] ?? $buyingPriceLatest->discount;
                  $values['discount_amount'] = $row['discount_amount'] ?? $buyingPriceLatest->discount_amount;
              }

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

                  QuotationCharge::create([
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
