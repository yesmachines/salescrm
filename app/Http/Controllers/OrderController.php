<?php

namespace App\Http\Controllers;

use App\Models\BuyingPrice;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\QuotationService;
use App\Models\PaymentTerm;
use App\Models\Supplier;
use App\Models\QuotationAvailability;
use App\Models\QuotationItem;
use App\Models\QuotatationPaymentTerm;
use App\Models\QuotationInstallation;
use App\Models\OrderClient;
use App\Models\OrderItem;
use App\Models\OrderSupplier;
use App\Models\OrderPayment;
use App\Models\OrderCharge;
use App\Models\OrderServiceRequest;
use App\Models\Quotation;
use App\Models\Order;
use App\Models\QuotationCharge;
use App\Models\QuotationOptionalItem;
use App\Models\SalesCommission;
use App\Services\CurrencyService;
use App\Services\DivisionService;
use App\Services\ProductService;
use App\Services\SupplierService;
use DB;

class OrderController extends Controller
{
  public function index(Request $request, OrderService $orderService)
  {
    return view('orders.index');
  }
  public function completedOrders(OrderService $orderService)
  {
    return view('orders.completed');
  }
  //
  public function createNewFromQuote(
    $id,
    QuotationService $quotationService,
    CurrencyService $currencyService,
    ProductService $productService,
    SupplierService $supplierService,
    DivisionService $divisionService
  ) {
    $quotation = $quotationService->getQuote($id);

    $selling_price = $quotation->total_amount;
    $margin_price  = $quotation->gross_margin;

    $currency_rate = [];
    if ($quotation->preferred_currency) {
      $currency_rate = DB::table('currency_conversion')->where('currency', $quotation->preferred_currency)->first();
      if ($currency_rate) {
        $selling_price = $quotation->total_amount * $currency_rate->standard_rate;
        $margin_price  = $quotation->gross_margin * $currency_rate->standard_rate;
      }
    }

    $quote_avail = QuotationAvailability::where("quotation_id", $quotation->id)->first();
    $quote_install = QuotationInstallation::where("quotation_id", $quotation->id)->first();
    $quote_clientpayment = QuotatationPaymentTerm::where("quotation_id", $quotation->id)->get();
    $quote_charges = QuotationCharge::where("quotation_id", $quotation->id)->get();
    $quote_items = QuotationItem::where("quotation_id", $quotation->id)->get();

    // sevice time against order value
    $serviceTime = $this->serviceEstimation();
    $serviceEstimatedTime = 0;
    // check for the service time with order value
    foreach ($serviceTime as $service) {
      if ($selling_price >= $service["min_value"] && ($selling_price <= $service["max_value"] && $service["max_value"] != -1)) {
        $serviceEstimatedTime = $service["time"];
      } else if ($service["max_value"] ==  -1 && $selling_price >= $service["min_value"]) {
        $serviceEstimatedTime = $service["time"];
      }
    }

    if ($serviceEstimatedTime &&  $quote_install) {
      $quote_install->installation_periods = $serviceEstimatedTime;
    }

    $customProductType = false;
    $selectedSuppliers = [];
    $buyingPrice = [];
    foreach ($quote_items as $item) {
      $selectedSuppliers[] = $item->brand_id;
      if ($item->product->product_type == 'custom') {
        $customProductType = true;
      }
      // calculate buying price - OS
      if (!empty($item->product->buyingPrice) && isset($item->product->buyingPrice[0])) {
        $bprice = $item->product->buyingPrice[0]->buying_price;
        $bcurrency = $item->product->buyingPrice[0]->buying_currency;

        $item->buying_price = $bprice * $item->quantity;
        $item->buying_price = number_format((float)$item->buying_price, 2, '.', '');
        $item->buying_currency = $bcurrency;

        // bp currency conversion
        $currency_rate_buying = DB::table('currency_conversion')->where('currency', $bcurrency)->first();
        if ($currency_rate_buying) {
          $buyingPrice[] = $item->buying_price * $currency_rate_buying->standard_rate;
        }
      }
    }
    $buying_price = number_format((float)array_sum($buyingPrice), 2, '.', '');

    //$selectedSuppliers =  $items->pluck('brand_id')->toArray();

    $terms =  PaymentTerm::where("status", 1)->where("parent_id", 0)->get();
    $suppliers = Supplier::whereIn("id", $selectedSuppliers)->get();
    $service_employee = Employee::where("division", "LIKE", "serv")
      ->where("status", 1)
      ->get();

    $currencies =   $currencyService->getAllCurrency();
    $localSuppliers = $supplierService->getLocalSupplier();


    $divisions = $divisionService->getDivisionList();
    $managers = $productService->employeesList();

    return view('orders.createnew', compact(
      'quotation',
      'terms',
      'suppliers',
      'quote_avail',
      'quote_items',
      'quote_install',
      'currency_rate',
      'selling_price',
      'margin_price',
      'quote_clientpayment',
      'service_employee',
      'quote_charges',
      'currencies',
      'customProductType',
      'buying_price',
      'localSuppliers',
      'divisions',
      'managers',
      'serviceEstimatedTime'
    ));
  }

  private function serviceEstimation()
  {
    // sevice time against order value
    $serviceTime = [
      array("min_value" => 0, "max_value" => 50000, "time" => "4 hours"),
      array("min_value" => 50001, "max_value" => 100000, "time" => "2 days"),
      array("min_value" => 100001, "max_value" => 300000, "time" => "3 days"),
      array("min_value" => 300001, "max_value" => 500000, "time" => "4 days"),
      array("min_value" => 500001, "max_value" => 1000000, "time" => "6 days"),
      array("min_value" => 1000001, "max_value" => 1500000, "time" => "12 days"),
      array("min_value" => 1500001, "max_value" => -1, "time" => "18 days"),
    ];

    return $serviceTime;
  }

  public function saveOrderStep1(Request $request, OrderService $orderService)
  {
    $request->validate([
      'customer_id'   => 'required',
      'company_id'    => 'required',
      'quotation_id'  => 'required',
      'created_by'    => 'required',
      'os_date'       => 'required',
      'selling_price' => 'required|decimal:0,4',
      'price_basis'   => 'required',
      'delivery_term' => 'required',
      'status'        => 'required'
    ]);

    $input =  $request->all();

    // create
    $order = $orderService->insertOrder($input);

    if ($order && !empty($order)) {
      //client order
      $client = [
        'order_id'          => $order->id,
        'price_basis'       => $input['price_basis'],
        'delivery_term'     => $input['delivery_term'],
        'promised_delivery' => $input['promised_delivery']
      ];
      $orderClient = $orderService->saveOrderClient($client);

      // payment term
      if (isset($input['clientpayment'])) {
        $payments = [
          'order_id'      => $order->id,
          'section_type'  => $input['section_type'],
          'payment_term'  => $input['clientpayment'],
        ];
        $orderService->insertOrderPayment($payments);
      }
    }


    return response()->json(['success' => 'Summary added successfully', 'data' => $order]);
  }

  public function saveOrderItemStep2(Request $request, OrderService $orderService)
  {
    $request->validate([
      'order_id'            => 'required',
      'material_status'     => 'required',
      'material_details'    => 'required',
      'delivery_address'    => 'required',
      'contact_person'      => 'required',
      'contact_phone'       => 'required',
      'item.*.item_name'    => 'required',
      'item.*.quantity'     => 'required',
      'item.*.total_amount' => 'required|decimal:0,4',
      'item.*.yes_number'   => 'required',
      //'item.*.buying_price'   => 'required|decimal:0,4',
      // 'item.*.buying_currency' => 'required',
    ]);

    $input =  $request->all();

    // update order
    $oupdate = [
      'material_status'  => $input['material_status'],
      'material_details' => $input['material_details'],
    ];
    if (isset($input['status'])) {
      $oupdate['status'] = $input['status'];
    }
    $order = $orderService->updateOrder($oupdate, $input['order_id']);

    // update order client
    $cupdate = [
      'order_id'               => $input['order_id'],
      // 'installation_training'  => $input['installation_training'],
      //  'service_expert'         => $input['service_expert'],
      //  'estimated_installation' => $input['estimated_installation'],
      'delivery_address'       => $input['delivery_address'],
      'contact_person'         => $input['contact_person'],
      'contact_email'          => $input['contact_email'],
      'contact_phone'          => $input['contact_phone'],
      'remarks'                => $input['delivery_remarks'],
      'is_demo'                => isset($input['is_demo']) ? $input['is_demo'] : 0,
      'demo_by'                => $input['demo_by']
    ];
    $orderService->saveOrderClient($cupdate);

    // order Items save
    if (isset($input['item'])) {
      $items = [
        'order_id'      => $input['order_id'],
        'item'          => $input['item'],
      ];
      $orderService->saveOrderItems($items);

      // Recalculate buying price total

      $buyingPrice = [];

      foreach ($input['item'] as $item) {


        // calculate buying price - OS
        if (!empty($item['buying_price']) && isset($item['buying_price'])) {
          $bprice = $item['buying_price'];
          $bcurrency =  $item['buying_currency'];

          $currency_rate = DB::table('currency_conversion')->where('currency', $bcurrency)->first();
          if ($currency_rate) {
            $buyingPrice[] = $bprice * $currency_rate->standard_rate;
          }
        }
      }
      $buying_price = number_format((float)array_sum($buyingPrice), 2, '.', '');
    }

    return response()->json([
      'success' => 'Items and delivery added successfully',
      'data' => $order,
      'buyingPrice' => $buying_price
    ]);
  }

  public function saveServiceStep3(Request $request, OrderService $orderService)
  {
    $request->validate([
      'order_id'            => 'required',
      // 'material_status'     => 'required',
      // 'material_details'    => 'required',
      // 'delivery_address'    => 'required',

    ]);

    $input =  $request->all();
    if (
      trim($input['installation_training']) || trim($input['service_expert']) ||
      trim($input['estimated_installation'])
    ) {
      // update order client
      $cupdate = [
        'order_id'               => $input['order_id'],
        'installation_training'  => $input['installation_training'],
        'service_expert'         => $input['service_expert'],
        'estimated_installation' => $input['estimated_installation'],
      ];
      $orderService->saveOrderClient($cupdate);
    }

    if (
      trim($input['site_readiness']) || trim($input['training_requirement']) ||
      trim($input['consumables']) || trim($input['warranty_period']) || trim($input['special_offers']) ||
      trim($input['documents_required']) || trim($input['machine_objective']) || trim($input['fat_test'])
      || trim($input['fat_expectation']) || trim($input['sat_objective'])
    ) {
      // order Service requests save
      $supdate = [
        'order_id'               => $input['order_id'],
        'site_readiness'         => $input['site_readiness'],
        'training_requirement'   => $input['training_requirement'],
        'consumables'            => $input['consumables'],
        'warranty_period'        => $input['warranty_period'],
        'special_offers'         => $input['special_offers'],
        'documents_required'     => $input['documents_required'],
        'machine_objective'      => $input['machine_objective'],
        'fat_test'               => $input['fat_test'],
        'fat_expectation'        => $input['fat_expectation'],
        'sat_objective'          => $input['sat_objective'],
      ];
      $orderService->saveOrderService($supdate);
    }

    $order = $orderService->getOrder($input['order_id']);

    return response()->json([
      'success' => 'Service requests added successfully',
      'data' => $order,

    ]);
  }

  public function saveSupplierTermStep4(Request $request, OrderService $orderService)
  {

    $validate = [
      'order_id'          => 'required',
      'buying_price_total'      => 'required|decimal:0,4',
      'projected_margin'  => 'required|decimal:0,4',
      'supplier.*.country_id'    => 'required',
      'supplier.*.supplier_id'   => 'required',
      'supplier.*.price_basis'   => 'required',
      'supplier.*.delivery_term' => 'required',
      'supplierpayment.*.payment_term'  => 'required',
      'supplierpayment.*.status' => 'required',
    ];
    if ($request->has('isadditionalcharges') && $request->input('isadditionalcharges') == 1) {
      $validate['charges.*.title']      = 'required';
      $validate['charges.*.considered'] = 'required|decimal:0,4';
    }

    $request->validate($validate);
    $input =  $request->all();

    // update order
    $oupdate = [
      'order_id'         => $input['order_id'],
      'buying_price'     => $input['buying_price_total'],
      'projected_margin' => $input['projected_margin']

    ];
    if (isset($input['status'])) {
      $oupdate['status'] = $input['status'];
    }
    if (isset($input['manager_approval'])) {
      $oupdate['manager_approval'] = $input['manager_approval'];
    }
    $order = $orderService->updateOrder($oupdate, $input['order_id']);

    // Supplier delivery term
    if (isset($input['supplier'])) {
      $supplier = [
        'order_id'  => $order->id,
        'supplier'  => $input['supplier'],
      ];
      $orderService->saveOrderSupplier($supplier);
    }

    // payment term
    if (isset($input['supplierpayment'])) {
      $payments = [
        'order_id'      => $order->id,
        'section_type'  => $input['section_type'],
        'payment_term'  => $input['supplierpayment'],
      ];
      $orderService->insertOrderPayment($payments);
    }

    // Additional charges
    if (isset($input['charges'])) {
      $charges = [
        'order_id' => $order->id,
        'charges'  => $input['charges'],
      ];
      $orderService->insertOrderCharges($charges);
    }

    return response()->json(['success' => 'Supplier delivery and charges added successfully', 'data' => $order]);
  }

  public function downloadOS($id, OrderService $orderService)
  {
    // $header = view('orders.partials.header_pdf')
    //     ->render();

    $orderDetails = $orderService->getOrder($id);
    $optionalItems = QuotationOptionalItem::where('quotation_id', $orderDetails->quotation_id)->get();
    $salesCommission = SalesCommission::where('quotation_id', $orderDetails->quotation_id)->get();
    if ($orderDetails->status == 'draft') {
      $watermark = "DRAFT COPY";
    } else {
      $watermark = "YESMACHINERY";
    }

    $body = view('orders.os_summary')
      ->with(compact(
        'orderDetails',
        'optionalItems',
        'salesCommission'
      ))
      ->render();

    $mpdf = new \Mpdf\Mpdf([
      'tempDir' => public_path('uploads/temp'),
      'mode' => 'utf-8',
      'autoScriptToLang' => true,
      'autoLangToFont' => true,
      'autoVietnamese' => true,
      'autoArabic' => true,
      // 'margin_top' => 8,
      // 'margin_bottom' => 8,
      'format' => 'A4',
      'setAutoBottomMargin' => 'stretch',
      'setAutoTopMargin' => 'stretch',
      // 'autoMarginPadding' => 2,
      'useOddEven' => 1
    ]);

    $mpdf->useSubstitutions = true;

    $mpdf->SetWatermarkText($watermark, 0.1);
    $mpdf->showWatermarkText = true;
    $mpdf->SetTitle('OS-' . $orderDetails->os_number . '.pdf');
    // $mpdf->WriteHTML($header);
    $mpdf->WriteHTML($body);
    $mpdf->Output('OS-' . $orderDetails->os_number . '.pdf', 'I');
  }

  public function edit(
    OrderService $orderService,
    $id,
    QuotationService $quotationService,
    CurrencyService $currencyService,
    ProductService $productService,
    SupplierService $supplierService,
    DivisionService $divisionService
  ) {

    $order = $orderService->getOrder($id);
    $quote_avail = QuotationAvailability::where("quotation_id", $order->quotation_id)->first();
    $quote_items = OrderItem::where('order_id', $id)->get();
    $terms = PaymentTerm::where("status", 1)->where("parent_id", 0)->get();
    $deliveryPoints = OrderClient::where('order_id', $id)->first();


    $suppliers = OrderSupplier::where('order_id', $id)->get();
    $serviceRequest = OrderServiceRequest::where('order_id', $id)->first();
    $paymentTermsSupplier = OrderPayment::where('order_id', $id)->where('section_type', 'supplier')->get();
    $orderCharges = OrderCharge::where('order_id', $id)->get();
    $quotation = Quotation::where('id', $order->quotation_id)->first();
    $paymentTermsClient = OrderPayment::where('order_id', $id)->where('section_type', 'client')->get();
    $service_employee = Employee::where("division", "LIKE", "serv")
      ->where("status", 1)
      ->get();

    $customProductType = false;
    foreach ($quote_items as $item) {
      if ($item->product->product_type == 'custom') {
        $customProductType = true;
      }
    }
    $currencies = $currencyService->getAllCurrency();

    $localSuppliers = $supplierService->getLocalSupplier();
    $divisions = $divisionService->getDivisionList();
    $managers = $productService->employeesList();


    return view('orders.edit', compact(
      'paymentTermsClient',
      'quotation',
      'order',
      'quote_avail',
      'quote_items',
      'suppliers',
      'terms',
      'deliveryPoints',
      'paymentTermsSupplier',
      'orderCharges',
      'service_employee',
      'customProductType',
      'currencies',
      'localSuppliers',
      'divisions',
      'managers',
      'serviceRequest'
    ));
  }

  public function show(OrderService $orderService, $id, QuotationService $quotationService)
  {
    $order = $orderService->getOrder($id);
    $quote_avail = QuotationAvailability::where("quotation_id", $order->quotation_id)->first();
    $quote_items = OrderItem::where('order_id', $id)->get();
    $terms = PaymentTerm::where("status", 1)->get();
    $deliveryPoints = OrderClient::where('order_id', $id)->first();
    $suppliers = OrderSupplier::where('order_id', $id)->get();
    $paymentTermsSupplier = OrderPayment::where('order_id', $id)->where('section_type', 'supplier')->get();
    $orderCharges = OrderCharge::where('order_id', $id)->get();
    $quotation = Quotation::where('id', $order->quotation_id)->first();
    $paymentTermsClient = OrderPayment::where('order_id', $id)->where('section_type', 'client')->get();

    return view('orders.show', compact(
      'paymentTermsClient',
      'quotation',
      'order',
      'quote_avail',
      'quote_items',
      'suppliers',
      'terms',
      'deliveryPoints',
      'paymentTermsSupplier',
      'orderCharges'
    ));
  }

  public function editOrderStep1(Request $request, OrderService $orderService)
  {

    $input =  $request->all();


    $order = $orderService->updateOrder($input, $input['order_id']);

    if ($order && !empty($order)) {
      $client = [
        'order_id'          => $order->id,
        'price_basis'       => $input['price_basis'],
        'delivery_term'     => $input['delivery_term'],
        'promised_delivery' => $input['promised_delivery']
      ];
      $orderClient = $orderService->saveOrderClient($client);

      $paymentTermsClient = [];
      if (isset($input['clientpayment'])) {
        // update/ create payment term
        $payments = [
          'order_id'      => $order->id,
          'section_type'  => $input['section_type'],
          'payment_term'  => $input['clientpayment'],
        ];
        $orderService->updateOrderPayment($payments);

        // update payment term section
        $paymentTermsClient = $orderService->getOrderPayment($payments);
        return response()->json([
          'success' => 'Summary added successfully',
          'isPayment' => 1,
          'data' => view(
            'orders._edit._clientpayment',
            ['paymentTermsClient' => $paymentTermsClient]
          )->render()
        ]);
      }
    }

    return response()->json(['success' => 'Summary added successfully', 'isPayment' => 0, 'data' => $order]);
  }

  public function editOrderItemStep2(Request $request, OrderService $orderService)
  {

    $request->validate([
      //'order_id'            => 'required',
      'item.*.yes_number'   => 'required',
      //'item.*.buying_price'   => 'required|decimal:0,4',
      // 'item.*.buying_currency' => 'required',
    ]);

    $input =  $request->all();
    $oupdate = [
      'material_status'  => $input['material_status'],
      'material_details' => $input['material_details']
    ];
    if (isset($input['status'])) {
      $oupdate['status'] = $input['status'];
    }
    $order = $orderService->updateOrder($oupdate, $input['order_id']);
    $cupdate = [
      'order_id'               => $input['order_id'],
      // 'installation_training'  => $input['installation_training'],
      // 'service_expert'         => $input['service_expert'],
      // 'estimated_installation' => $input['estimated_installation'],
      'delivery_address'       => $input['delivery_address'],
      'contact_person'         => $input['contact_person'],
      'contact_email'          => $input['contact_email'],
      'contact_phone'          => $input['contact_phone'],
      'remarks'                => $input['delivery_remarks'],
      'is_demo'                => isset($input['is_demo']) ? $input['is_demo'] : 0,
      'demo_by'                => $input['demo_by']
    ];
    $orderService->saveOrderClient($cupdate);
    if (isset($input['item'])) {
      $items = [
        'order_id'      => $input['order_id'],
        'item'          => $input['item'],
      ];
      $orderService->saveOrderItems($items);
    }

    return response()->json(['success' => 'Items and delivery added successfully', 'data' => $order]);
  }
  public function editServiceStep3(Request $request, OrderService $orderService)
  {
    $request->validate([
      'order_id'            => 'required',
      // 'material_status'     => 'required',
      // 'material_details'    => 'required',
      // 'delivery_address'    => 'required',

    ]);

    $input =  $request->all();
    if (
      trim($input['installation_training']) || trim($input['service_expert']) ||
      trim($input['estimated_installation'])
    ) {
      // update order client
      $cupdate = [
        'order_id'               => $input['order_id'],
        'installation_training'  => $input['installation_training'],
        'service_expert'         => $input['service_expert'],
        'estimated_installation' => $input['estimated_installation'],
      ];
      $orderService->saveOrderClient($cupdate);
    }

    // order Service requests save
    if (
      trim($input['site_readiness']) || trim($input['training_requirement']) ||
      trim($input['consumables']) || trim($input['warranty_period']) || trim($input['special_offers']) ||
      trim($input['documents_required']) || trim($input['machine_objective']) || trim($input['fat_test'])
      || trim($input['fat_expectation']) || trim($input['sat_objective'])
    ) {
      $supdate = [
        'order_id'               => $input['order_id'],
        'site_readiness'         => $input['site_readiness'],
        'training_requirement'   => $input['training_requirement'],
        'consumables'            => $input['consumables'],
        'warranty_period'        => $input['warranty_period'],
        'special_offers'         => $input['special_offers'],
        'documents_required'     => $input['documents_required'],
        'machine_objective'      => $input['machine_objective'],
        'fat_test'               => $input['fat_test'],
        'fat_expectation'        => $input['fat_expectation'],
        'sat_objective'          => $input['sat_objective'],
      ];
      $orderService->saveOrderService($supdate);
    }

    $order = $orderService->getOrder($input['order_id']);

    return response()->json([
      'success' => 'Service requests added successfully',
      'data' => $order,

    ]);
  }
  public function editSupplierTermStep4(Request $request, OrderService $orderService)
  {
    $input =  $request->all();

    $oupdate = [
      'order_id'         => $input['order_id'],
      'buying_price'     => $input['buying_price_total'],
      'projected_margin' => $input['projected_margin']

    ];
    if (isset($input['status'])) {
      $oupdate['status'] = $input['status'];

    }
    if (isset($input['manager_approval'])) {
      $oupdate['manager_approval'] = $input['manager_approval'];
    }
    $order = $orderService->updateOrder($oupdate, $input['order_id']);

    if (isset($input['supplier'])) {
      $supplier = [
        'order_id'  => $order->id,
        'supplier'  => $input['supplier'],
      ];
      $orderService->saveOrderSupplier($supplier);
    }
    $paymentTermsSupplier = [];
    $additionalCharges = [];

    if (isset($input['supplierpayment'])) {
      $payments = [
        'order_id'      => $order->id,
        'section_type'  => $input['section_type'],
        'payment_term'  => $input['supplierpayment'],
      ];
      $orderService->updateOrderPayment($payments);

      // update payment term section
      $paymentTermsSupplier = $orderService->getOrderPayment($payments);
    }

    if (isset($input['charges'])) {
      $charges = [
        'order_id' => $order->id,
        'charges'  => $input['charges'],
      ];
      $orderService->updateOrderCharges($charges);

      // update additional charge section
      $additionalCharges = $orderService->getOrderCharges($order->id);
    }

    if (!empty($paymentTermsSupplier) || !empty($additionalCharges)) {
      return response()->json([
        'success' => 'Supplier delivery and charges added successfully',
        'isPayment' => 1,
        'data' => view(
          'orders._edit._supplierpayment',
          ['paymentTermsSupplier' => $paymentTermsSupplier]
        )->render(),
        'data2' => view(
          'orders._edit._additionalcharges',
          ['orderCharges' => $additionalCharges]
        )->render()
      ]);
    }


    return response()->json([
      'success' => 'Supplier delivery and charges added successfully',
      'isPayment' => 0,
      'data' => $order
    ]);
  }

  public function deletePaymentTerm(Request $request, OrderService $orderService)
  {
    $input = $request->all();

    $orderService->deletePaymentTerm($input["paymentId"]);

    return response()->json(['success' => 'Payment Term deleted successfully', 'data' => 1]);
  }

  public function deleteCharges(Request $request, OrderService $orderService)
  {

    $input = $request->all();

    $orderService->deleteOrderCharge($input["chargeId"]);

    return response()->json(['success' => 'Additional Charges deleted successfully', 'data' => 1]);
  }

  // public function orderHistoryDetailsInsert(Request $request, OrderService $orderService)
  // {
  //   $input = $request->all();

  //   //$orderService->insertOrderHistoryDetails($input);
  //   return response()->json($input['order_id']);
  // }

  // public function  orderHistoryLoad(Request $request, OrderService $orderService)
  // {
  //   $data = $request->all();
  //   $id = $data['order_id'];

  //   $order = $orderService->getOrderById($id);
  //   $customer = ($order->customer) ? $order->customer->fullname : '';
  //   //$orderService->loadOrderHistoryDetails($id);
  //   $histories = $order->orderHistory;

  //   $data2 = view('orders.partials._listhistory')
  //     ->with(compact('histories', 'customer'))
  //     ->render();


  //   return response()->json(['data' => $data2]);
  // }

  // public function orderHistoryDelete(Request $request, OrderService $orderService)
  // {
  //   $data = $request->all();
  //   $id = $data['order_history_id'];
  //   $orderService->deleteOrderHistoryDetails($id);
  //   return response()->json($id);
  // }
  // public function replyCommentDelete(Request $request, OrderService $orderService)
  // {
  //   $data = $request->all();
  //   $id = $data['reply_comment_id'];
  //   $orderService->deleteReplyComments($id);
  //   return response()->json($id);
  // }

  public function deleteOrderItem(Request $request, OrderService $orderService)
  {
    $data = $request->all();
    $id = $data['order_item_id'];
    $orderService->deleteOrderItem($id);
    return response()->json($id);
  }

  // public function updateDeliveryByIdHistoryUpdate(Request $request, OrderService $orderService)
  // {
  //   $data = $request->all();
  //   $orderService->updateOrderDeliveryDetailsByIdUpdate($data);
  //   return response()->json($data['order_delivery_id']);
  // }

  // public function commentReplyInsert(Request $request, OrderService $orderService)
  // {
  //   $input = $request->all();
  //   $orderService->insertCommentReply($input);
  //   return response()->json($input['order_id']);
  // }
  public function destroy($id, OrderService $orderService)
  {

    $orderService->deleteOrder($id);


    return redirect()->back()
      ->with('success', 'Order deleted successfully');
  }
  public function managerApproval(Request $request, $orderId)
{

    $order = Order::findOrFail($orderId);

    $order->manager_approval = $request->status;
    $order->status = 'open';
    $order->approved_by = auth()->id();
    $order->save();

    return response()->json(['message' => 'Order status updated successfully!']);
}

}
