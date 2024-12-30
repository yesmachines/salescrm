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

        $customPriceQuotes = QuotationCustomPrice::select(
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
          ->where('quotation_id', $id)
          ->get();

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

          $customPriceData = json_decode($request['customprice'], true);

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

          foreach ($customPriceData as $field) {
            if (is_string($field['field_name'])) {
              $data[$field['field_name']] = $field['value'];
            }
          }


          $quotationCustomPrice = QuotationCustomPrice::updateOrCreate($conditions, $data);

          $quotationCustomPrices = QuotationCustomPrice::where('quotation_id', $quotationId)->get();

          $quotationCharges = QuotationCharge::where('quotation_id', $quotationId)->get();

          foreach ($quotationCharges as $charge) {
            $fieldName = $charge->short_code;

            $sum = $quotationCustomPrices->sum(function ($customPrice) use ($fieldName) {
              return $customPrice[$fieldName] ?? 0;
            });

            $charge->amount = $sum;
            $charge->save();
          }

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
          $item = QuotationItem::where('item_id',$itemId)->where('quotation_id',$quotationId)->first();
          $buyingPrice = BuyingPrice::where('product_id', $itemId)
          ->latest()
          ->first();
          $customPrice = QuotationCustomPrice::where('product_id',$itemId)->where('quotation_id',$quotationId)->first();
          return response()->json([
            'gross_price' => $buyingPrice->gross_price,
            'purchase_discount' => $buyingPrice->discount,
            'purchase_discount_amount' => $buyingPrice->discount_amount,
            'buying_price' => $buyingPrice->buying_price,
            'buying_currency' => $buyingPrice->buying_currency,
            'unit_margin' => $item->margin_price,
            'mosp' => $item->product->margin_percent,
            'unit_price' => $item->unit_price,
            'selling_currency' => $item->currency,
            'packing' => $customPrice->packing,
            'road_transport_to_port' => $customPrice->road_transport_to_port,
            'freight' => $customPrice->freight,
            'insurance' => $customPrice->insurance,
            'clearing' => $customPrice->clearing,
            'boe' => $customPrice->boe,
            'handling_and_local_transport' => $customPrice->handling_and_local_transport,

          ]);
        }

      }
