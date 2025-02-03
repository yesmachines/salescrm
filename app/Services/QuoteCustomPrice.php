<?php

namespace App\Services;

use App\Models\QuotationCustomPrice;
use App\Models\QuotationCharge;
use App\Models\BuyingPrice;
use App\Models\CustomPrice;
class QuoteCustomPrice
{
  /**
  * Create Custom Prices for a Quotation
  *
  * @param object $quotation
  * @param array $userData
  * @return void
  */
  public function createQuotationCustom($quotation, array $userData)
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
        'price_basis' => $userData['price_basis'],
        'updated_by' => auth()->id(),
      ];


      $buyingPriceLatest = BuyingPrice::where('product_id', $itemIds[$index])
      ->orderBy('created_at', 'desc')
      ->first();

      if ($buyingPriceLatest) {
        $values['gross_price'] = $buyingPriceLatest->gross_price;
        $values['discount'] = $buyingPriceLatest->discount;
        $values['discount_amount'] = $buyingPriceLatest->discount_amount;
        $values['buying_price'] = $buyingPriceLatest->buying_price;
        $values['buying_currency'] = $buyingPriceLatest->buying_currency;
      }

      QuotationCustomPrice::updateOrCreate($attributes, $values);
    }

    foreach ($customPriceData as $row) {
      unset($row['source']);
      if (!isset($row['product_id'], $row['id'])) {
        continue;
      }


      $productId = $row['product_id'];
      $buyingPriceLatest = BuyingPrice::where('product_id', $productId)->orderBy('created_at', 'desc')->first();

      foreach ($row as $key => $value) {
        if (in_array($key, ['id', 'product_id']) || is_null($value)) {
          continue;
        }
        $customData = CustomPrice::where('id', $row['id'])->first();
        $attributes = [
          'quotation_id' => $quotationId,
          'product_id' => $productId,

        ];

        $values = [
          $key => $value,
          'updated_by' => auth()->id(),
          'final_buying_price'=> $customData['final_buying_price'],
          'mobp'=> $customData['mobp'],
          'margin_amount_bp'=> $customData['margin_amount_bp'],
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
    if (isset($customPriceData)) {
      $quote_visisble=$userData['is_visible'];
      $summarizedCharges = [];


      $productIdExist = array_filter($customPriceData, fn($item) => isset($item['product_id']));

      $productIdNotExist = array_filter($customPriceData, fn($item) => !isset($item['product_id']));

      $summedCharges = [];


      foreach ($productIdExist as $item) {
        unset($item['id'], $item['product_id'], $item['source']);

        foreach ($item as $chargeName => $chargeAmount) {
          if ($chargeAmount !== null) {
            if (isset($summedCharges[$chargeName])) {
              $summedCharges[$chargeName] += $chargeAmount;
            } else {
              $summedCharges[$chargeName] = $chargeAmount;
            }
          }
        }
      }

      $visibilityFlags=$userData['is_visible'];
      $mergedCharges = [];
      $summedChargesArray = array_values($summedCharges);
      foreach ($summedChargesArray as $index => $chargeAmount) {
        $chargeName = array_keys($summedCharges)[$index];
        $isVisible = isset($visibilityFlags[$index]) ? $visibilityFlags[$index] : 1;
        $mergedCharges[] = [
          'title' => $chargeName,
          'amount' => $chargeAmount,
          'quote_visible' => $isVisible
        ];
      }

      $quotationId = $quotationId;


      foreach ($mergedCharges as $charge) {

        $shortcodeTitle = strtolower(str_replace(' ', '_', $charge['title']));
        $title = str_replace('_', ' ', $charge['title']);
        $title = ucwords($title);

        QuotationCharge::create([
          'quotation_id' => $quotationId,
          'title' => $title,
          'amount' => $charge['amount'],
          'short_code' => $shortcodeTitle,
          'quote_visible' => $charge['quote_visible'],
        ]);
      }
    }

    return response()->json(['message' => 'Custom prices updated successfully!']);
  }
  public function updateQuotationCustom($quotation, array $userData)
  {

    $itemIds = $userData['item_id'];
    $quotationId = $userData['quotation_id'] ?? $quotation->id;
    $customPriceData = json_decode($userData['customprice'], true);

    $createdProductId = null;

    // First loop: Check if the record exists, and create if it doesn't
    for ($index = 0; $index < count($itemIds); $index++) {

      $attributes = [
        'quotation_id' => $quotationId,
        'product_id' => $itemIds[$index],
      ];

      // Check if the record exists, and only create if it doesn't exist
      $existingRecord = QuotationCustomPrice::where($attributes)->first();

      if (!$existingRecord) {
        $values = [
          'selling_price' => $userData['unit_price'][$index],
          'margin_price' => $userData['product_margin'][$index],
          'margin_percent' => $userData['margin_percent'][$index],
          'selling_currency' => $userData['product_currency'][$index],
          'updated_by' => auth()->id(),
        ];

        // Create the new record
        $customPrice = QuotationCustomPrice::create(array_merge($attributes, $values));

        // Get the created product_id
        $createdProductId = $customPrice->product_id;
      }
    }

    foreach ($customPriceData as $row) {

      unset($row['source']);
      if (!isset($row['product_id'], $row['id'])) {
        continue;
      }

      $productId = $row['product_id'];

      if ($createdProductId && $row['product_id'] == $createdProductId) {
        $customData = CustomPrice::where('id', $row['id'])->first();


        if ($customData) {
          $values = [
            'final_buying_price' => $customData['final_buying_price'],
            'mobp' => $customData['mobp'],
            'margin_amount_bp' => $customData['margin_amount_bp'],
          ];

          // Update the existing QuotationCustomPrice record with the new values
          QuotationCustomPrice::where('quotation_id', $quotationId)
          ->where('product_id', $createdProductId)
          ->update($values);
        }
      }
      $buyingPriceLatest = BuyingPrice::where('product_id',$createdProductId)
      ->orderBy('created_at', 'desc')
      ->first();


      if ($buyingPriceLatest) {
        // Update values from the latest buying price if it exists
        $values['gross_price'] = $buyingPriceLatest->gross_price ?? ($row['gross_price'] ?? null);
        $values['discount'] = $buyingPriceLatest->discount ?? ($row['discount'] ?? null);
        $values['discount_amount'] = $buyingPriceLatest->discount_amount ?? ($row['discount_amount'] ?? null);
        $values['buying_price'] = $buyingPriceLatest->buying_price;
        $values['buying_currency'] = $buyingPriceLatest->buying_currency;
        $values['price_basis'] =$userData['price_basis'] ;
        QuotationCustomPrice::where('quotation_id', $quotationId)
        ->where('product_id', $createdProductId)
        ->update($values);
      }
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

        QuotationCustomPrice::updateOrCreate($attributes, $values);
      }
    }

    // Clean up existing charges and reapply them
    QuotationCharge::where('quotation_id', $quotationId)->delete();

    // Handle charge updates
    $this->updateCharges($quotationId, $userData, $customPriceData);

    return response()->json(['message' => 'Custom prices updated successfully!']);
  }

  private function updateCharges($quotationId, $userData, $customPriceData)
  {
    $quote_visible = $userData['is_visible'];
    $summarizedCharges = [];

    $productIdExist = array_filter($customPriceData, fn($item) => isset($item['product_id']));
    $productIdNotExist = array_filter($customPriceData, fn($item) => !isset($item['product_id']));

    $summedCharges = [];

    foreach ($productIdExist as $item) {
      unset($item['id'], $item['product_id'], $item['source']);

      foreach ($item as $chargeName => $chargeAmount) {
        if ($chargeAmount !== null) {
          if (isset($summedCharges[$chargeName])) {
            $summedCharges[$chargeName] += $chargeAmount;
          } else {
            $summedCharges[$chargeName] = $chargeAmount;
          }
        }
      }
    }

    $mergedCharges = [];
    $summedChargesArray = array_values($summedCharges);
    foreach ($summedChargesArray as $index => $chargeAmount) {
      $chargeName = array_keys($summedCharges)[$index];
      $isVisible = isset($quote_visible[$index]) ? $quote_visible[$index] : 1;
      $mergedCharges[] = [
        'title' => $chargeName,
        'amount' => $chargeAmount,
        'quote_visible' => $isVisible,
      ];
    }

    foreach ($mergedCharges as $charge) {
      $shortcodeTitle = strtolower(str_replace(' ', '_', $charge['title']));
      $title = str_replace('_', ' ', $charge['title']);
      $title = ucwords($title);

      QuotationCharge::create([
        'quotation_id' => $quotationId,
        'title' => $title,
        'amount' => $charge['amount'],
        'short_code' => $shortcodeTitle,
        'quote_visible' => $charge['quote_visible'],
      ]);
    }

    if (isset($userData['charge_name']) && isset($userData['charge_amount'])) {
      $chargeNames = $userData['charge_name'];
      $chargeAmounts = $userData['charge_amount'];

      for ($i = 0; $i < count($chargeNames); $i++) {
        $chargeName = $chargeNames[$i];
        $chargeAmount = $chargeAmounts[$i];

        if (!is_null($chargeAmount) && $chargeAmount !== '') {
          $isVisible = 1;

          $shortcodeTitle = strtolower(str_replace(' ', '_', $chargeName));

          $chargeData = [
            'quotation_id' => $quotationId,
            'title' => $chargeName,
            'amount' => $chargeAmount,
            'short_code' => $shortcodeTitle,
            'quote_visible' => $isVisible,
          ];

          QuotationCharge::create($chargeData);
        }
      }
    } else {
      foreach ($productIdNotExist as $productDatas) {
        if (!empty($productDatas)) {
          foreach ($productDatas as $key => $productData) {
            $isVisible = 1;
            $shortcodeTitle = strtolower(str_replace(' ', '_', $key));

            $chargeData = [
              'quotation_id' => $quotationId,
              'title' => $key,
              'amount' => $productData,
              'short_code' => $shortcodeTitle,
              'quote_visible' => $isVisible,
            ];

            QuotationCharge::create($chargeData);
          }
        }
      }
    }
  }

}
