<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomField;
use App\Models\CustomPrice;
use App\Models\CustomPriceQuote;
use App\Models\PaymentTerm;
use App\Models\Quotation;
use App\Models\QuotationCharge;
use App\Models\QuotationCustomPrice;
use App\Models\QuotationItem;
use App\Models\ProductPriceHistory;
use App\Models\BuyingPrice;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomPriceExport;

use DB;

class CustomPriceController extends Controller
{
  public function getCustomFieldsByPriceBasis(Request $request)
  {

    $paymentTermId = $request->input('payment_term_id');

    $customFields = CustomField::where('price_basis', $paymentTermId)
    ->where('status', '1')
    ->orderBy('sort_order', 'asc')
    ->get();

    return response()->json($customFields);
  }
  public function getCustomPrice($id)
  {

    $customPrices = CustomPrice::where('product_history_id', $id)
    ->orwhere('product_id', $id)
    ->select(
      'id',
      'product_id',
      'packing',
      'road_transport_to_port',
      'freight',
      'insurance',
      'clearing',
      'boe',
      'handling_and_local_transport',
      'customs',
      'delivery_charge',
      'mofaic',
      'surcharges'
      )

      ->first();

      return response()->json(['customPrices' => $customPrices]);
    }
    public function getQuotationCustomPrice($id)
    {

      $customQuote = CustomPriceQuote::where('quotation_id', $id)->get();
      $customPriceIds = $customQuote->map(function($customQuote) {
        return $customQuote->custom_price_id;
      });
      $customPrices=CustomPrice::whereIn('id',  $customPriceIds)->select(
        'id',
        'product_id',
        'packing',
        'road_transport_to_port',
        'freight',
        'insurance',
        'clearing',
        'boe',
        'handling_and_local_transport',
        'customs',
        'delivery_charge',
        'mofaic',
        'surcharges'
        )->get();
        return response()->json(['customPrices' => $customPrices]);
      }

      public function downloadCustomPrice($id)
      {

        $quotation = Quotation::findOrFail($id);
        $priceBasis = PaymentTerm::where('short_code', $quotation->price_basis)->first();
        $customHeadings = CustomField::where('price_basis', $priceBasis->id)->get();
        return \Excel::download(
          new CustomPriceExport($id, $customHeadings),
          'custom_price_quotation_items.xlsx'
        );
      }
      public function editQuotationCustomPrice($id)
      {

        $customPriceQuotes = QuotationCustomPrice::where('quotation_id', $id)
        ->get(['id', 'product_id', 'packing', 'road_transport_to_port', 'freight', 'insurance', 'clearing', 'boe', 'handling_and_local_transport', 'customs', 'delivery_charge', 'mofaic', 'surcharges']);


        $customPriceQuotes = $customPriceQuotes->map(function ($quote) {
          foreach ($quote->toArray() as $key => $value) {
            if ($value === 0.0 || $value === null) {
              unset($quote[$key]);
            }
          }

          $quote->source = 'custom_price';
          return $quote;
        });


        $customPrices = [];
        $unmatchedCharges = [];


        foreach ($customPriceQuotes as $quote) {
          $customPrices[] = $quote;
        }


        $quotationCharges = QuotationCharge::where('quotation_id', $id)->get();


        foreach ($quotationCharges as $charge) {
          $isMatched = false;
          foreach ($customPrices as $customPrice) {
            if (isset($customPrice->{$charge->short_code}) && $customPrice->{$charge->short_code} !== null) {
              $isMatched = true;
              break;
            }
          }
          if (!$isMatched) {
            $unmatchedCharges[] = [
              $charge->title => $charge->amount,
            ];
          }
        }


        $customPrices = array_merge($customPrices, $unmatchedCharges);


        return response()->json($customPrices);
      }

      public function deleteCustomPriceQuote($quoteId, Request $request)
      {
        $productId = $request->query('product_id');
        $deleted = QuotationCustomPrice::where('quotation_id', $quoteId)
        ->where('product_id', $productId)
        ->delete();


      }
      // public function quoteCustomEdit(Request $request)
      // {
      //
      //   $sellingPrice = $request['selling_price'];
      //   $marginPrice = $request['margin_price'];
      //   $marginPercentage = $request['margin_percentage'];
      //   $quoteCurrency = $request['quote_currency'];
      //   $buyingGrossPrice = $request['buying_gross_price'];
      //   $buyingPurchaseDiscount = $request['buying_purchase_discount'] ?? 0;
      //   $buyingPurchaseDiscountAmount = $request['buying_purchase_discount_amount'];
      //   $buyingPrices = $request['buying_prices'];
      //   $buyingCurrency = $request['buying_currency'];
      //   $priceBasis = $request['price_basis'];
      //   $quotationId = $request['quotation_id'];
      //   $productId = $request['product_id'];
      //
      //   $customPriceData = json_decode($request['customprice'], true);
      //
      //   $conditions = [
      //     'quotation_id' => $quotationId,
      //     'product_id' => $productId,
      //   ];
      //
      //   $data = [
      //     'selling_price' => $sellingPrice,
      //     'margin_price' => $marginPrice,
      //     'margin_percent' => $marginPercentage,
      //     'selling_currency' => $quoteCurrency,
      //     'gross_price' => $buyingGrossPrice,
      //     'discount' => $buyingPurchaseDiscount,
      //     'discount_amount' => $buyingPurchaseDiscountAmount,
      //     'buying_price' => floatval(str_replace(',', '', $buyingPrices)),
      //     'buying_currency' => $buyingCurrency,
      //     'price_basis' => $priceBasis,
      //   ];
      //
      //   foreach ($customPriceData as $field) {
      //     if (is_string($field['field_name'])) {
      //       $data[$field['field_name']] = $field['value'];
      //     }
      //   }
      //
      //
      //   $quotationCustomPrice = QuotationCustomPrice::updateOrCreate($conditions, $data);
      //
      //   $quotationCustomPrices = QuotationCustomPrice::where('quotation_id', $quotationId)->get();
      //
      //   $quotationCharges = QuotationCharge::where('quotation_id', $quotationId)->get();
      //
      //   foreach ($quotationCharges as $charge) {
      //     $fieldName = $charge->short_code;
      //
      //     $sum = $quotationCustomPrices->sum(function ($customPrice) use ($fieldName) {
      //       return $customPrice[$fieldName] ?? 0;
      //     });
      //
      //     $charge->amount = $sum;
      //     $charge->save();
      //   }
      //
      //   $quotationItem = QuotationItem::where('quotation_id', $quotationId)
      //   ->where('item_id', $productId)
      //   ->first();
      //
      //   if ($quotationItem) {
      //
      //     $subTotal = $sellingPrice * $quotationItem->quantity;
      //     $totalAfterDiscount = floatval(str_replace(',', '', $buyingPrices)) - $buyingPurchaseDiscountAmount;
      //     $marginPrice = (($subTotal - $totalAfterDiscount) / $subTotal) * 100;
      //     $quotationItem->unit_price = $sellingPrice;
      //     $quotationItem->subtotal = $subTotal;
      //     $quotationItem->discount = $buyingPurchaseDiscount;
      //     $quotationItem->total_after_discount = $totalAfterDiscount;
      //     $quotationItem->margin_price = $marginPrice;
      //     $quotationItem->currency = $quoteCurrency;
      //     $quotationItem->unit_margin = $marginPrice;
      //
      //     $quotationItem->save();
      //   }
      //
      //   return response()->json(['success' => true]);
      // }

      public function quoteCustomEdit(Request $request)
      {

        $sellingPrice = $request['selling_price'];
        $marginPrice = $request['margin_price'];
        $marginPercentage = $request['margin_percentage'];
        $quoteCurrency = $request['quote_currency'];
        $buyingGrossPrice = $request['buying_gross_price'];
        $buyingPurchaseDiscount = $request['buying_purchase_discount'] ?? 0;
        $buyingPurchaseDiscountAmount = $request['buying_purchase_discount_amount'];
        $buyingPrices = $request['buying_prices'];
        $buyingCurrency = $request['buying_currency'];
        $priceBasis = $request['price_basis'];
        $quotationId = $request['quotation_id'];
        $productId = $request['product_id'];

        $fields = [
          'packing' => $request['packing'],
          'road_transport_to_port' => $request['road_transport_to_port'],
          'freight' => $request['freight'],
          'insurance' => $request['insurance'],
          'clearing' => $request['clearing'],
          'boe' => $request['boe'],
          'handling_and_local_transport' => $request['handling_and_local_transport'],
          'customs' => $request['customs'],
          'delivery_charge' => $request['delivery_charge'],
          'mofaic' => $request['mofaic'],
          'surcharges' => $request['surcharges'],
        ];

        // $nonZeroFields = array_filter($fields, function($value) {
        //   return $value != 0;
        // });

        $conditions = [
          'quotation_id' => $quotationId,
          'product_id' => $productId,
        ];

        $data = [
          'selling_price' => $sellingPrice,
          'margin_price' => $marginPrice,
          'margin_percent' => $marginPercentage,
          'selling_currency' => $quoteCurrency,
          'gross_price' => $buyingGrossPrice,
          'discount' => $buyingPurchaseDiscount,
          'discount_amount' => $buyingPurchaseDiscountAmount,
          'buying_price' => floatval(str_replace(',', '', $buyingPrices)),
          'buying_currency' => $buyingCurrency,
          'price_basis' => $priceBasis,
        ];

        // foreach ($nonZeroFields as $key => $value) {
        //   $data[$key] = $value;
        // }
        $quotationCustomPrice = QuotationCustomPrice::updateOrCreate($conditions, $fields);

        $quotationCustomPrices = QuotationCustomPrice::where('quotation_id', $quotationId)->get();
        $quotation=Quotation::where('id', $quotationId)->first();
        $paymenterm=PaymentTerm::where('short_code',$quotation->price_basis)->first();
        $customFields=CustomField::where('price_basis',  $paymenterm->id)->get();
        $quotationCharges = QuotationCharge::where('quotation_id', $quotationId)->get();


        $fieldSums = [];


        foreach ($customFields as $customField) {
          $shortCode = $customField->short_code;


          $sum = $quotationCustomPrices
          ->filter(function ($quotePrice) use ($shortCode) {
            return isset($quotePrice[$shortCode]) && is_numeric($quotePrice[$shortCode]);
          })
          ->sum(function ($quotePrice) use ($shortCode) {
            return $quotePrice[$shortCode];
          });

          $fieldSums[$shortCode] = $sum;
        }

        $quotationChargesArray = $quotationCharges->pluck('amount', 'short_code')->toArray();

        foreach ($fieldSums as $key => $value) {
          if (!is_numeric($value)) {
            throw new Exception("Invalid amount value for charge '{$key}': {$value}");
          }
        }

        foreach ($quotationChargesArray as $chargeName => $chargeAmount) {
          if (!array_key_exists($chargeName, $fieldSums)) {
            $fieldSums[$chargeName] = $chargeAmount;
          }
        }

        foreach ($fieldSums as $chargeName => $chargeAmount) {
          if (!is_numeric($chargeAmount)) {

            throw new Exception("Invalid amount value for charge '{$chargeName}': {$chargeAmount}");
          }

          $quotationCharge = $quotationCharges->firstWhere('short_code', $chargeName);
          if ($quotationCharge) {

            $quotationCharge->amount = $chargeAmount;
            $quotationCharge->save();
          } else {

            QuotationCharge::create([
              'quotation_id' => $quotationId,
              'title' => ucfirst(str_replace('_', ' ', $chargeName)),
              'short_code' => $chargeName,
              'amount' => $chargeAmount
            ]);
          }
        }

        QuotationCharge::where('quotation_id', $quotationId)
        ->where('amount', '0')
        ->delete();

        $quotationItem = QuotationItem::where('quotation_id', $quotationId)
        ->where('item_id', $productId)
        ->first();

        if ($quotationItem) {
          $subTotal = $sellingPrice * $quotationItem->quantity;
          $totalAfterDiscount = floatval(str_replace(',', '', $buyingPrices)) - $buyingPurchaseDiscountAmount;
          $marginPrice = (($subTotal - $totalAfterDiscount) / $subTotal) * 100;
          $quotationItem->unit_price = $sellingPrice;
          $quotationItem->subtotal = $subTotal;
          $quotationItem->discount = $buyingPurchaseDiscount;
          $quotationItem->total_after_discount = $totalAfterDiscount;
          $quotationItem->margin_price = $marginPrice;
          $quotationItem->currency = $quoteCurrency;
          $quotationItem->unit_margin = $marginPrice;

          $quotationItem->save();
        }

        return response()->json(['success' => true]);
      }

      public function editQuotationCharge($id)
      {

        $quotationCharges = QuotationCharge::where('quotation_id', $id)->get();
        $charges = $quotationCharges->map(function ($charge) {
          return [
            'charge_name' => $charge->title,
            'charge_amount' => $charge->amount,
            'quote_visible' => $charge->quote_visible,
          ];
        });

        return response()->json([
          'quotation_id' => $id,
          'charges' => $charges,
        ]);
      }
      public function getItemDetails($itemId, $quotationId)
      {
        $item = QuotationItem::where('item_id', $itemId)
        ->where('quotation_id', $quotationId)
        ->first();
        $buyingPrice = BuyingPrice::where('product_id', $itemId)
        ->latest()
        ->first();
        $customPrice = QuotationCustomPrice::where('product_id', $itemId)
        ->where('quotation_id', $quotationId)
        ->first();

        if ($customPrice) {
          // Get all attributes (fields) of the model
          $attributes = $customPrice->getAttributes();

          // Filter out fields where value is not null and get the field names and values
          $nonNullFields = array_filter($attributes, function($value) {
            return !is_null($value);
          });

          // Now, merge both regular fields and non-null fields into one array
          $response = [
            'gross_price' => $customPrice->gross_price,
            'purchase_discount' => $customPrice->discount,
            'purchase_discount_amount' => $customPrice->discount_amount,
            'buying_price' => $customPrice->buying_price,
            'buying_currency' => $customPrice->buying_currency,
            'unit_margin' => $customPrice->margin_price,
            'mosp' => $customPrice->margin_percent,
            'unit_price' => $customPrice->selling_price,
            'selling_currency' => $customPrice->selling_currency,
            // 'packing' => $customPrice->packing,
            // 'road_transport_to_port' => $customPrice->road_transport_to_port,
            // 'freight' => $customPrice->freight,
            // 'insurance' => $customPrice->insurance,
            // 'clearing' => $customPrice->clearing,
            // 'boe' => $customPrice->boe,
            // 'handling_and_local_transport' => $customPrice->handling_and_local_transport,
          ];

          // Merge non-null fields into the response (overwrite existing values with non-null ones)
          $response = array_merge($response, $nonNullFields);

          return response()->json($response);
        }

        // If no custom price is found, return a response with an error or empty fields
        return response()->json([
          'message' => 'Custom price not found.',
          'data' => null,
        ], 404);
      }

      public function deleteQuotationCharge(Request $request)
      {

        $quotationId = $request->input('quotation_id');
        $chargeName = $request->input('charge_name');
        $chargeAmount = $request->input('charge_amount');

        // Find the charge and delete it
        $quotationCharge = QuotationCharge::where('quotation_id', $quotationId)
        ->where('title', $chargeName)
        ->where('amount', $chargeAmount)
        ->first();

        if ($quotationCharge) {
          $chargeId = $quotationCharge->id;


          $quotationCharge->delete();


        }
        return response()->json([
          'message' => 'Charge deleted successfully',

        ]);


      }


    }
