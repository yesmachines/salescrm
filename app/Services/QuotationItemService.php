<?php

namespace App\Services;

use App\Models\QuotationItem;
use App\Models\QuotationCharge;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use App\Models\QuotationTerm;
use App\Models\QuotationField;
use App\Models\PaymentTerm;
use App\Models\Product;
use App\Models\QuotationAvailability;
use App\Models\QuotatationPaymentTerm;
use App\Models\QuotationInstallation;
use App\Models\QuotationOptionalItem;
use Illuminate\Database\Eloquent\Collection;
use DB;


class QuotationItemService
{

  public function createQuotationItem($quotes, array $userData)
  {
    // quotation items
    $itemIds = $userData['item_id'];
    $brand = $userData['brand_id'];
    $itemDescriptions = $userData['item_description'];
    $unitPrices = $userData['unit_price'];
    $quantities = $userData['quantity'];
    $subtotals = $userData['subtotal'];
    $discounts = $userData['discount'];
    $marginPrice = $userData['margin_amount_row'];
    $quoteCurrency = $userData['quote_currency'];
    $totalAfterDiscounts = $userData['total_after_discount'];
    $discountStatus = $userData['discount_status'];
    $unitMargin = $userData['product_margin'];


    for ($index = 0; $index < count($itemIds); $index++) {

      QuotationItem::create([
        'quotation_id' => $quotes->id,
        'item_id' => $itemIds[$index],
        'description' => $itemDescriptions[$index],
        'unit_price' => $unitPrices[$index],
        'quantity' => $quantities[$index],
        'subtotal' => $subtotals[$index],
        'discount' => $discounts[$index],
        'total_after_discount' => $totalAfterDiscounts[$index],
        'brand_id' =>  $brand[$index],
        'margin_price' =>  $marginPrice[$index],
        'discount_status' => $discountStatus[$index],
        'unit_margin' => $unitMargin[$index],
      ]);
    }

    if (isset($userData['charge_name']) && isset($userData['charge_amount'])) {
      $chargeType = $userData['charge_name'];
      $chargeAmount = $userData['charge_amount'];
      $quoteVisible = $userData['is_visible'];
    }

    if (isset($chargeType)) {

      foreach ($chargeType as $index => $charge) {
        if ($chargeAmount[$index] !== null) {
          $shortcodeTitle = strtolower(str_replace(' ', '_', $charge));
          //charge table save
          $newQuotationCharge = QuotationCharge::create([
            'quotation_id' => $quotes->id,
            'title' => $charge,
            'amount' => $chargeAmount[$index],
            'short_code' => $shortcodeTitle,
            'quote_visible' => 1,
          ]);

          $existingQuotationCharge = QuotationField::where('title', $charge)->first();
          if (!$existingQuotationCharge) {
            $insert = [
              'title' => $charge,
              'amount' => $chargeAmount[$index],
              'short_code' => $shortcodeTitle,
              'field_type' => 'quotation_charges',
              'is_default' => 0
            ];

            $newQuotationCharge = QuotationField::create($insert);
          }
        }
      }
    }

    // payment terms
    foreach ($userData['payment_name'] as $index => $paymentTerm) {
      if ($paymentTerm !== null) {
        $shortcodepayment = strtolower(str_replace(' ', '_', $paymentTerm));

        $newQuotationPayment = QuotationTerm::create([
          'quotation_id' => $quotes->id,
          'title' => $userData['payment_name'][$index],
          'group_title' => 'payment_terms',
          'description' => $userData['payment_description'][$index],
          'short_code' => $shortcodepayment,

        ]);
        $newQuotationPayment = QuotatationPaymentTerm::create([
          'quotation_id' => $quotes->id,
          'title' => $userData['payment_name'][$index],
          'payment_amount' => $userData['payment_description'][$index],
        ]);
      }
    }

    // delivery terms
    if (isset($userData['delivery_terms'])) {
      $shortcodeTerms = strtolower(str_replace(' ', '_', $userData['delivery_terms']));
      $newQuotationTerms = QuotationTerm::create([
        'quotation_id' => $quotes->id,
        'title' => $userData['delivery_terms'],
        'group_title' => 'availability',
        'short_code' => $shortcodeTerms,
        'description' => $userData['delivery_weeks'],
      ]);
      $newQuotationTerms = QuotationAvailability::create([
        'quotation_id' => $quotes->id,
        'stock_status' => $userData['delivery_terms'],
        'working_weeks' => $userData['delivery_weeks'],
        'working_period' => $userData['working_period'],
      ]);
    }
    // quotation terms
    $termTitle = $userData['term_title'];
    $termDescription = $userData['term_description'];

    foreach ($termTitle as $index => $title) {
      if ($title !== null) {
        $shortcodeTerms = strtolower(str_replace(' ', '_', $title));

        if (isset($termDescription[$index]) && empty(trim($termDescription[$index]))) {
          continue;
        }

        $newQuotationTerm = QuotationTerm::create([
          'quotation_id' => $quotes->id,
          'title' => $title,
          'description' => trim($termDescription[$index]),
          'short_code' => $shortcodeTerms,
          'group_title' => 'quotation_terms',
        ]);
      }
    }

    // installation
    $insertInstall = [];
    if (isset($userData['installation_by']) && !is_null($userData['installation_by'])) {
      $insertInstall["installation_by"] = $userData['installation_by'];
    }
    if (isset($userData['installation_periods']) && !is_null($userData['installation_periods'])) {
      $insertInstall["installation_periods"] = $userData['installation_periods'];
    }
    if (isset($userData['install_accommodation']) && !is_null($userData['install_accommodation'])) {
      $insertInstall["install_accommodation"] = $userData['install_accommodation'];
    }
    if (isset($userData['install_tickets']) && !is_null($userData['install_tickets'])) {
      $insertInstall["install_tickets"] = $userData['install_tickets'];
    }
    if (isset($userData['install_transport']) && !is_null($userData['install_transport'])) {
      $insertInstall["install_transport"] = $userData['install_transport'];
    }
    if (isset($userData['buyer_site']) && !is_null($userData['buyer_site'])) {
      $insertInstall["install_buyer_site"] = $userData['buyer_site'];
    }

    if (!empty($insertInstall)) {
      $insertInstall['quotation_id'] =  $quotes->id;

      $newQuotationTerms = QuotationInstallation::create($insertInstall);
    }
    foreach ($userData['optional_name'] as $index => $optionalName) {
      if ($optionalName !== null) {
        $optionalItems = QuotationOptionalItem::create([
          'quotation_id' => $quotes->id,
          'item_name' => $optionalName,
          'quantity' => $userData['optional_quantity'][$index],
          'amount' => $userData['optional_amount'][$index],
        ]);
      }
    }
  }

  public function getQuotionItem($id): Collection
  {

    return Collection::make(QuotationItem::where('quotation_id', $id)->get());
  }
  public function getQuotionId($id): ?QuotationItem
  {

    return QuotationItem::where('quotation_id', $id)->first();
  }


  public function updateQuoteItem($userData)
  {

    $quotationId = $userData['quotation_id'];

    QuotationItem::where('quotation_id', $quotationId)->delete();

    // quotation items Insert
    $itemCount = count($userData['item_id']);
    if ($itemCount > 0) {
      for ($index = 0; $index < $itemCount; $index++) {
        QuotationItem::create([
          'quotation_id' => $quotationId,
          'item_id' => $userData['item_id'][$index],
          'description' => $userData['item_description'][$index],
          'unit_price' => $userData['unit_price'][$index],
          'quantity' => $userData['quantity'][$index],
          'subtotal' => $userData['subtotal'][$index],
          'discount' => $userData['discount'][$index],
          'total_after_discount' => $userData['total_after_discount'][$index],
          'brand_id' =>   $userData['brand_id'][$index],
          'margin_price' =>  $userData['margin_amount_row'][$index],
          'discount_status' => $userData['discount_status'][$index],
          'unit_margin' => $userData['product_margin'][$index],
        ]);
      }
    }

    // Quotation charges
    QuotationCharge::where('quotation_id', $quotationId)->delete();

    if (isset($userData['charge_name'])) {
      foreach ($userData['charge_name'] as $index => $charge) {
        if ($charge !== null && $charge !== '') {
          $shortcodeTitle = strtolower(str_replace(' ', '_', $charge));

          QuotationCharge::create([
            'quotation_id' => $quotationId,
            'title' => $charge,
            'amount' => $userData['charge_amount'][$index],
            'short_code' => $shortcodeTitle,
            'quote_visible'=>1,
          ]);
        }
      }
    }

    // quotation terms
    QuotationTerm::where('quotation_id', $quotationId)->where('group_title', 'quotation_terms')->delete();

    if (isset($userData['term_title'])) {
      foreach ($userData['term_title'] as $index => $title) {
        if ($title !== null && $title !== '') {
          $shortcodeTerms = strtolower(str_replace(' ', '_', $title));

          if (isset($userData['term_description'][$index]) && empty(trim($userData['term_description'][$index]))) {
            continue;
          }

          QuotationTerm::create([
            'quotation_id' => $quotationId,
            'title' => $title,
            'description' => trim($userData['term_description'][$index]),
            'short_code' => $shortcodeTerms,
            'group_title' => 'quotation_terms',
          ]);
        }
      }
    }

    // Payment Terms
    QuotationTerm::where('quotation_id', $quotationId)->where('group_title', 'payment_terms')->delete();
    QuotatationPaymentTerm::where('quotation_id', $quotationId)->delete();
    if(isset($userData['payment_name'])){
      foreach ($userData['payment_name'] as $index => $paymentTerm) {
        if ($paymentTerm !== null) {

          $shortcodepayment = strtolower(str_replace(' ', '_', $paymentTerm));

          $newQuotationPayment = QuotationTerm::create([
            'quotation_id' => $quotationId,
            'title' => $userData['payment_name'][$index],
            'group_title' => 'payment_terms',
            'description' => $userData['payment_description'][$index],
            'short_code' => $shortcodepayment,

          ]);
          $newQuotationPayment = QuotatationPaymentTerm::create([
            'quotation_id' => $quotationId,
            'title' => $userData['payment_name'][$index],
            'payment_amount' => $userData['payment_description'][$index],
          ]);
        }
      }
    }

    // Quotation Availability
    QuotationTerm::where('quotation_id', $quotationId)->where('group_title', 'availability')->delete();

    if (isset($userData['delivery_terms'])) {
      $shortcodeTerms = strtolower(str_replace(' ', '_', $userData['delivery_terms']));

      $newQuotationTerms = QuotationTerm::create([
        'quotation_id' => $quotationId,
        'title' => $userData['delivery_terms'],
        'group_title' => 'availability',
        'short_code' => $shortcodeTerms,
        'description' => $userData['delivery_weeks'],
      ]);
      $newQuotationTerms = QuotationAvailability::updateOrCreate(
        ['quotation_id' => $quotationId],
        [
          'stock_status' => $userData['delivery_terms'],
          'working_weeks' => $userData['delivery_weeks'],
          'working_period' => $userData['working_period'],
        ]
      );
    }

    $insertInstall = [];
    if (isset($userData['installation_by']) && !is_null($userData['installation_by'])) {
      $insertInstall["installation_by"] = $userData['installation_by'];
    }
    if (isset($userData['installation_periods']) && !is_null($userData['installation_periods'])) {
      $insertInstall["installation_periods"] = $userData['installation_periods'];
    }
    if (isset($userData['install_accommodation']) && !is_null($userData['install_accommodation'])) {
      $insertInstall["install_accommodation"] = $userData['install_accommodation'];
    }
    if (isset($userData['install_tickets']) && !is_null($userData['install_tickets'])) {
      $insertInstall["install_tickets"] = $userData['install_tickets'];
    }
    if (isset($userData['install_transport']) && !is_null($userData['install_transport'])) {
      $insertInstall["install_transport"] = $userData['install_transport'];
    }
    if (isset($userData['buyer_site']) && !is_null($userData['buyer_site'])) {
      $insertInstall["install_buyer_site"] = $userData['buyer_site'];
    }

    if (!empty($insertInstall)) {

      QuotationInstallation::updateOrCreate(
        ['quotation_id' => $quotationId],
        $insertInstall
      );
    }

    QuotationOptionalItem::where('quotation_id', $quotationId)->delete();
    foreach ($userData['optional_name'] as $index => $optionalName) {
      if ($optionalName !== null) {
        $optionalItems = QuotationOptionalItem::create([
          'quotation_id' =>$quotationId,
          'item_name' => $optionalName,
          'quantity' => $userData['optional_quantity'][$index],
          'amount' => $userData['optional_amount'][$index],
        ]);
      }
    }
  }
  public function getQuotionItemData($id): Collection
  {
    return QuotationItem::where('quotation_id', $id)->get();
  }


}
