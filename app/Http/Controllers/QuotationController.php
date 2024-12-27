<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuotationService;
use App\Services\LeadService;
use App\Services\LeadHistoryService;
use App\Services\EmployeeService;
use App\Services\CustomerService;
use App\Services\QuoteHistoryService;
use App\Services\SupplierService;
use App\Services\CategoryService;
use App\Services\CountryService;
use App\Services\EmployeeManagerService;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EmailNotification;
use Notification;
use App\Services\UserService;
use App\Services\CreateWordDocxService;
use App\Services\RegionService;
use Illuminate\Http\RedirectResponse;
use App\Services\OrderService;
use App\Services\QuotationItemService;
use App\Services\ProductService;
use App\Services\QuotationChargeService;
use App\Models\QuotationCharge;
use App\Models\Product;
use App\Models\Quotation;
use App\Services\QuotationTermService;
use App\Models\QuotationItem;
use App\Models\QuotationField;
use App\Services\DivisionService;
use App\Models\PaymentTerm;
use DB;
use App\Helpers\CurrencyConverter;
use App\Services\CreateOrderPdfService;
use App\Models\Orders;
use App\Models\QuotationTerm;
use App\Models\QuotationInstallation;
use App\Models\ProductPriceHistory;
use App\Models\QuotatationPaymentTerm;
use App\Models\QuotationAvailability;
use App\Services\QuotationOptionalService;
use App\Models\Currency;
use App\Services\CurrencyService;

class QuotationController extends Controller
{
  //
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(
    Request $request,
    QuotationService $quotationService
  ) {

    $quoteStatuses = $quotationService->getQuoteStatus();

    //
    // $input = $request->all();
    //
    // $input['is_active'] = [0, 1];
    //
    // $arrIds = [];
    // $empid = $request->session()->get('employeeid');
    // if (Auth::user()->hasAnyRole(['divisionmanager', 'salesmanager'])) {
    //     $arrIds[] = $empid;
    // } elseif (Auth::user()->hasRole('coordinators')) {
    //     $arrIds[] = $empid;
    //
    //     $managers = $employeeManagerService->getManagers($empid);
    //     $arrIds = array_merge($arrIds, $managers);
    // }
    // if (!empty($arrIds)) $input['assigned_to'] = $arrIds;
    //
    // $input['status_id'] = [1, 2, 3, 4, 5]; // exclude win
    //
    //
    // $quotations = $quotationService->getAllQuotes($input);

    return view('quotation.index', compact('quoteStatuses'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(
    QuotationService $quotationService,
    CustomerService $custService,
    EmployeeService $employeeService,
    CategoryService $categoryService,
    SupplierService $supplierService,
    CountryService $countryService,
    DivisionService $divisionService,
    ProductService $productService

  ) {

    $companies = $custService->getCompanies();

    $employees = $employeeService->getAllEmployee();

    $quoteStatuses = $quotationService->getQuoteStatus();

    $categories = $categoryService->categoryArray();
    $suppliers = $supplierService->getAllSupplier();
    $countries = $countryService->getAllCountry();
    return view('quotation.create', compact(
      'companies',
      'quoteStatuses',
      'employees',
      'categories',
      'suppliers',
      'countries',
    ));
  }

  public function convertToQuotation(
    $id,
    QuotationService $quotationService,
    LeadService $leadService,
    CategoryService $categoryService,
    SupplierService $supplierService,
    ProductService $productService,
    QuotationChargeService $quotationChargeService,
    DivisionService $divisionService,
    CurrencyService $currencyService
  ) {


    $quoteStatuses = $quotationService->getQuoteStatus();

    $categories = $categoryService->categoryArray();
    $suppliers = $supplierService->getAllSupplier();

    $lead = $leadService->getLead($id);
    $quotationType = session('type');

    $products = $productService->getAllProduct();
    $quotationCharges = $quotationChargeService->getQuotationCharge();
    $quotationTerms = $quotationChargeService->getQuotationTerms();
    $paymentTerms =  PaymentTerm::where('parent_id', 0)->get();
    $currencies = $currencyService->getAllCurrency();
    $currencyConversions = DB::table('currency_conversion')->get();
    $suppliers = $supplierService->getAllSupplier();
    $divisions = $divisionService->getDivisionList();
    $managers = $productService->employeesList();
    $paymentCycles = $quotationChargeService->getPaymentCyclesList();
    $paymentTermList = PaymentTerm::where('parent_id', 0)->orderByDesc('isdefault')->get();

    if ($quotationType == 'service') {
      return view('quotation.service_convert', compact(
        'lead',
        'quoteStatuses',
        'categories',
        'suppliers',
        'products',
        'quotationCharges',
        'quotationTerms',
        'paymentTerms',
        'currencies',
        'currencyConversions',
        'divisions',
        'managers',
        'suppliers',
        'paymentCycles',
        'paymentTermList',
        'quotationType'
      ));
    } else {

      return view('quotation.convert', compact(
        'lead',
        'quoteStatuses',
        'categories',
        'suppliers',
        'products',
        'quotationCharges',
        'quotationTerms',
        'paymentTerms',
        'currencies',
        'currencyConversions',
        'divisions',
        'managers',
        'suppliers',
        'paymentCycles',
        'paymentTermList',
        'quotationType'
      ));
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(
    Request $request,
    QuotationService $quotationService,
    CustomerService $custService,
    LeadService $leadService,
    LeadHistoryService $historyService,
    QuoteHistoryService $quoteHistoryService,
    CreateWordDocxService $wordService,
    QuotationItemService $quotationItemService

  ) {
    $this->validate($request, [
      'quote_for'         => 'required',
      'supplier_id'       => 'required',
      'total_value'       => 'required',
      'total_margin_value'     => 'required',
      'submitted_date'   => 'required|date',
      'winning_probability'   => 'required',
      'closure_date'          => 'required',
      'status_id'             => 'required',
      'quote_currency'  => 'required',
      'price_basis'       => 'required',
      'currency_rate'    => 'required',
    ]);

    $data = $request->all();

    // During the lead covertion
    if (isset($data['lead_id']) && !empty($data['lead_id'])) {
      // update leads status
      $lead = $leadService->getLead($data['lead_id']);

      $leadService->updateLead($lead, ['status_id' => 6]); // converted = 6

      $historyService->updateHistory([
        'status_id' => 6, // converted = 6
        'comment'   => 'Enquiry converted to quotation'
      ], $lead->id);
    }
    // display status updates
    if ($request->has('draft')) {
      // draft
      $data['is_active'] = 0;
    } else if ($request->has('publish')) {
      // publish
      $data['is_active'] = 1;
    }
    // create new quotation
    $quotes = $quotationService->createQuote($data);

    $quotationItem = $quotationItemService->createQuotationItem($quotes, $data);
    // add Quote history
    $status = $quotationService->getQuoteStatusById($data['status_id']);
    $quoteHistoryService->addHistory([
      'status_id' => $data['status_id'],
      'comment'   => 'Quotation is created with status ' . $status->name
    ], $quotes->id);

    /***********************************************
     * Update Sales Commision - Calculation
     ***********************************************/
    $fields = [];
    array_push($fields, [
      'manager_id'    => $data['assigned_to'],
      'percent'       => 100,
      'commission_amount' => $data['total_margin_value']
    ]);
    $quotationService->createOrUpdateCommision($fields, $quotes->id);

    /************ Download Quotation **************/
    // $wordService->generateWordDocx($quotes);


    return redirect()->route('quotations.index')->with('success', 'Quotation created successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(
    $id,
    QuotationService $quotationService,
    QuoteHistoryService $quoteHistoryService,
    QuotationItemService $quotationItems,
    QuotationChargeService $quotationCharges,
    QuotationTermService $quoteTermService,
  ) {
    $quoteStatuses = $quotationService->getQuoteStatus();

    $histories = $quoteHistoryService->getHistories($id);

    $quotation = $quotationService->getQuote($id);

    $parentQuotes  = [];
    if ($quotation->root_parent_id <> 0) {
      $parentQuotes  = $quotationService->getQuotesTree($quotation);
    }
    $commissions = $quotationService->getSalesCommission($id);
    $quotationItems = $quotationItems->getQuotionItemData($id);
    $quotationCharges = $quotationCharges->getQuotionCharge($id);
    $quotationTerms = $quoteTermService->getQuotationTerms($id);

    return view('quotation.show',  compact(
      'quotation',
      'histories',
      'quoteStatuses',
      'parentQuotes',
      'commissions',
      'quotationItems',
      'quotationCharges',
      'quotationTerms'
    ));
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(
    $id,
    QuotationService $quotationService,
    CustomerService $custService,
    EmployeeService $employeeService,
    CountryService $countryService,
    CategoryService $categoryService,
    SupplierService $supplierService,
    RegionService $regionService,
    ProductService $productService,
    QuotationChargeService $quotationChargeService,
    QuotationItemService $quotationItemService,
    QuotationTermService  $quotationTermService,
    DivisionService $divisionService,
    QuotationOptionalService $quotationOptionalService,
    CurrencyService $currencyService
  ) {

    $quotation = $quotationService->getQuote($id);

    $countries = $countryService->getAllCountry();
    $companies = $custService->getCompanies();

    $customers = $custService->getAllCustomer(['company_id' => $quotation->company_id, 'status' => 1]);

    $quoteStatuses = $quotationService->getQuoteStatus();

    $employees = $employeeService->getAllEmployee();


    $userInfo = [];
    foreach ($employees as $i => $emp) {
      $userInfo[$i] = ['username' => $emp->user->name, 'id' => $emp->id];
    }
    $categories = $categoryService->categoryArray();
    $suppliers = $supplierService->getAllSupplier();

    $regions = [];
    if (isset($quotation->company->country_id) && $quotation->company->country_id) {

      $regions = $regionService->getAllRegion(['country_id' => $quotation->company->country_id]);
    }

    $commissions = $quotationService->getSalesCommission($id);

    //$productService->getAllProduct();
    // $products = Product::with('supplier')->get();


    $quotationItems = $quotationItemService->getQuotionItem($id);
    $quotationCharge = $quotationChargeService->getQuotionCharge($id);
    $quotationTermsList  = $quotationTermService->getQuotionTerm($id);
    $optionalItems = $quotationOptionalService->quotationOptionalItem($id);
    $paymentCycleList = QuotatationPaymentTerm::where('quotation_id', $id)->get();
    //$quotationTermService->getPaymentCycle($id);

    $quotationChargesList = $quotationChargeService->getQuotationCharge();
    $quotationTerms = $quotationChargeService->getQuotationTerms();

    $availability = QuotationAvailability::where('quotation_id', $id)->first();

    // $deliveryTerms = QuotationTerm::where('group_title', 'availability')
    //   ->where('quotation_id', $id)
    //   ->first();
    $paymentCycles = $quotationChargeService->getPaymentCyclesList();
    $currencies = $currencyService->getAllCurrency();

    $unitPrice = [];
    $total = [];
    $subtotal = [];
    if (!empty($quotation->preferred_currency)) {

      $conversionFromRate = DB::table('currency_conversion')
        ->where('currency', $quotation->preferred_currency)
        ->select('standard_rate')
        ->first();


      // if (!empty($quotationItems)) {
      //   foreach ($quotationItems as $key => $unitPriceInQuoteCu) {
      //     $unitPrice[] = CurrencyConverter::convertedFrom(
      //       $unitPriceInQuoteCu->unit_price,
      //       $conversionFromRate
      //     );
      //   }
      //   ///subtotal
      //   foreach ($quotationItems as $key => $subCu) {
      //     $subtotal[] = CurrencyConverter::convertedFrom(
      //       $subCu->subtotal,
      //       $conversionFromRate
      //     );
      //   }
      //   //total
      //   foreach ($quotationItems as $key => $totalCu) {
      //     $total[] = CurrencyConverter::convertedFrom(
      //       $totalCu->total_after_discount,
      //       $conversionFromRate
      //     );
      //   }
      // }
    }
    $divisions = $divisionService->getDivisionList();
    $managers = $productService->employeesList();
    $paymentTerms = PaymentTerm::where('parent_id', 0)->get();

    $products = collect();
    $brands = [];

    foreach ($quotationItems as $value) {
      //$category = Product::where('id', $value->item_id)->pluck('product_category')->first();
      // $products = $products->merge(Product::where('brand_id', $value->brand_id)
      //->where('product_category', $category)
      //  ->get());
      $brands[$value->supplier->brand] = Product::where('brand_id', $value->brand_id)->get();
    }

    $installation = QuotationInstallation::where('quotation_id', $id)->first();

    $paymentTermList = PaymentTerm::where('parent_id', 0)->orderByDesc('isdefault')->get();


    if ($quotation->quote_for == 'service') {
      return view('quotation.service_edit',  compact(
        'quotation',
        'categories',
        'suppliers',
        'customers',
        'employees',
        'quoteStatuses',
        'companies',
        'countries',
        'regions',
        'userInfo',
        'commissions',
        'brands',
        'quotationItems',
        'quotationCharge',
        'quotationTerms',
        'quotationChargesList',
        'quotationTermsList',
        'paymentCycles',
        //  'deliveryTerms',
        'paymentCycleList',

        // 'unitPrice',
        // 'subtotal',
        // 'total',
        'divisions',
        'managers',
        'paymentTerms',
        'currencies',
        'products',
        'paymentTermList',
        'installation',
        'availability',
        'optionalItems'
      ));
    } else {
      return view('quotation.edit',  compact(
        'quotation',
        'categories',
        'suppliers',
        'customers',
        'employees',
        'quoteStatuses',
        'companies',
        'countries',
        'regions',
        'userInfo',
        'commissions',
        'brands',
        'quotationItems',
        'quotationCharge',
        'quotationTerms',
        'quotationChargesList',
        'quotationTermsList',
        'paymentCycles',
        //  'deliveryTerms',
        'paymentCycleList',

        // 'unitPrice',
        // 'subtotal',
        // 'total',
        'divisions',
        'managers',
        'paymentTerms',
        'currencies',
        'products',
        'paymentTermList',
        'installation',
        'availability',
        'optionalItems'
      ));
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(
    Request $request,
    $id,
    QuotationService $quotationService,
    CustomerService $custService,
    QuoteHistoryService $quoteHistoryService,
    QuotationItemService $quotationItemService,
  ) {
    $this->validate($request, [
      'company'       => 'required',
      'fullname'      => 'required',
      'country_id'    => 'required',
      'email'         => 'email',
      'phone'         => 'required',
      'quote_for'     => 'required',
      // 'supplier_id'      => 'required',
      'total_value'            => 'required',
      'total_margin_value'     => 'required',
      'submitted_date'         => 'required|date',
      'winning_probability'   => 'required',
      'closure_date'          => 'required',
      'quote_currency'    => 'required',
      'price_basis'       => 'required',
      'currency_rate'     => 'required',
    ]);

    $data = $request->all();

    if ($request->has('company_id')) {
      if ($request->filled('company_id')) {
        // update company
        $company = $custService->getCompany($data['company_id']);
        $custService->updateCompany($company, $data);
      } else {
        // new company
        $company = $custService->createCompany($data);
        $data['company_id'] = $company->id;
      }
    }

    if ($request->has('customer_id')) {
      if ($request->filled('customer_id')) {
        // update customer
        $customer = $custService->getCustomer($data['customer_id']);
        $custService->updateCustomer($customer, $data);
      } else {
        // new customer
        $customer = $custService->createCustomer($data);
        $data['customer_id'] = $customer->id;
      }
    }

    // display status updates
    if ($request->has('draft')) {
      // draft
      $data['is_active'] = 0;
    } else if ($request->has('publish')) {
      // publish
      $data['is_active'] = 1;
    }
    // update quotation
    $quotation = $quotationService->getQuote($id);
    $quotationService->updateQuote($quotation, $data);

    // $itemData = $quotationItemService->getQuotionId($id);
    $qItemExits = QuotationItem::where('quotation_id', $id)->count();
    if ($qItemExits > 0) {
      $quotationItemService->updateQuoteItem($data);
    } else {
      $quotationItem = $quotationItemService->createQuotationItem($quotation, $data);
    }

    /***********************************************
     * Update Sales Commision - Calculation
     ***********************************************/
    $isManager = array_filter($data['manager_id'], function ($var) {
      return !empty($var);
    });
    if (isset($data['commission_amount']) && is_array($data['commission_amount'])) {
      $isCommision = array_filter($data['commission_amount'], function ($var) {
        return !empty($var);
      });
    }
    $fields = [];
    $commission = 0;
    $percent = 0;

    if (!empty($isManager) && !empty($isCommision)) {
      $field_count = count($data['manager_id']);
      for ($i = 0; $i < $field_count; $i++) {

        if ($data['manager_id'][$i] !=  $quotation->assigned_to) {
          // other manager commisions
          $commission += $data['commission_amount'][$i];
          $percent += $data['percent'][$i] ? $data['percent'][$i] : 0;
        }
        $fields[$i] = [
          'manager_id'    => $data['manager_id'][$i],
          'percent'       => $data['percent'][$i],
          'commission_amount' => $data['commission_amount'][$i]
        ];
      }

      // correct/ check margins - assigned manager
      foreach ($fields as $j => $data_user) {
        $balance = 0;
        $per = 0;
        if ($data_user['manager_id'] == $quotation->assigned_to) {
          $balance = ($quotation->gross_margin - $commission);
          $per = (100 - $percent);

          $fields[$j]['percent'] =  (int) $per;
          $fields[$j]['commission_amount'] =  $balance;
        } else {
          continue;
        }
      }
    } else {
      // On revision, save quote commissions
      array_push($fields, [
        'manager_id'    => $data['assigned_to'],
        'percent'       => 100,
        'commission_amount' => $data['total_margin_value']
      ]);
    }
    // UPDATE COMMISSION
    $quotationService->createOrUpdateCommision($fields, $id);

    return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id, QuotationService $quotationService, QuoteHistoryService $quoteHistoryService)
  {

    $quotationService->deleteQuote($id);

    // sales commision delete
    $quotationService->deleteSalesCommission(0, $id);

    // history of leads deleted
    $quoteHistoryService->deleteHistory($id);

    return redirect()->back()
      ->with('success', 'Quote deleted successfully');
  }

  public function reviseQuotation(
    $id,
    QuotationService $quotationService,
    QuoteHistoryService $quoteHistoryService,
    CustomerService $custService,
    EmployeeService $employeeService
  ) {
    // revise quotation
    $quotation = $quotationService->reviseQuote($id);

    /** Quotation History **/
    $quoteHistoryService->addHistory([
      'status_id' => $quotation->status_id,
      'comment'   => 'Quotation is revised to new version ' . $quotation->reference_no
    ], $quotation->id);


    return redirect()->route('quotations.edit', $quotation->id);
  }

  public function updateQuotationStatus(
    Request $request,
    QuotationService $quotationService,
    QuoteHistoryService $quoteHistoryService,
    EmployeeManagerService $employeeManagerService,
    UserService $userService
  ) {

    // $validator = Validator::make($request->all(), [
    //     'title' => 'required',
    //     'body' => 'required',
    // ]);

    // if ($validator->fails()) {
    //     return response()->json([
    //         'error' => $validator->errors()->all()
    //     ]);
    // }
    $input = $request->all();
    $id = $input['quotation_id'];

    $quotation = $quotationService->getQuote($id);

    $quotationService->updateQuote($quotation, $input);

    $quoteHistoryService->addHistory($input, $id);

    $statusinfo = $quoteHistoryService->getLatestStatus($id);

    // SEND NOTIFICATION MAIL
    $empid = $request->session()->get('employeeid');

    $users = $employeeManagerService->getCoordinators($empid, $userService);

    if (!empty($users)) {
      foreach ($users as $user) {
        $project = [
          'subject' => 'Quotation qualified to ' . $statusinfo->status,
          'greeting' => 'Hi ' . $user->name . ',',
          'body' => 'The quotation get qualified with status "' . $statusinfo->status . '" by the manager "' . $statusinfo->username . '".',
          'thanks' => 'Thank you',
          'actionText' => 'View Quotation',
          'actionURL' => url('quotations/' . $id)
        ];

        // Notification::send($user, new EmailNotification($project));
      }
    }

    return response()->json($statusinfo);
  }

  public function updateQuotationStatusWithOrder(
    Request $request,
    QuotationService $quotationService,
    QuoteHistoryService $quoteHistoryService,
    EmployeeManagerService $employeeManagerService,
    UserService $userService,
    OrderService $orderService
  ) {

    $request->validate([
      'quotation_id'  => 'required',
      'status_id'     => 'required'
    ]);

    $input = $request->all();

    /****
     * update quotation and add history
     ****/
    $id = $input['quotation_id'];
    $input['win_date'] =  \Carbon\Carbon::today();

    $quotation = $quotationService->getQuote($id);
    $quotationService->updateQuote($quotation, $input); // status, win
    // add history
    $quoteHistoryService->addHistory($input, $id);

    /****
     * create Order
     ****/
    // $field_count = count($input['yespo_no']);
    // $fields = [];

    // for ($i = 0; $i < $field_count; $i++) {
    //   $fields[$i] = [
    //     'yespo_no' => $input['yespo_no'][$i]['value'],
    //     'pono'   => $input['pono'][$i]['value'],
    //     'po_date' => $input['po_date'][$i]['value'],
    //     'po_received' => $input['po_received'][$i]['value'],
    //   ];
    // }
    // $orderId = $orderService->insertOrder($input, $fields);

    /****
     *  // SEND NOTIFICATION MAIL
     ****/
    $statusinfo = $quoteHistoryService->getLatestStatus($id);
    $empid = $request->session()->get('employeeid');
    $users = $employeeManagerService->getCoordinators($empid, $userService);

    if (!empty($users)) {
      foreach ($users as $user) {
        $project = [
          'subject' => 'Quotation qualified to ' . $statusinfo->status,
          'greeting' => 'Hi ' . $user->name . ',',
          'body' => 'The quotation get qualified with status "' . $statusinfo->status . '" by the manager "' . $statusinfo->username . '".',
          'thanks' => 'Thank you',
          'actionText' => 'View Quotation',
          'actionURL' => url('quotations/' . $id)
        ];

        // Notification::send($user, new EmailNotification($project));
      }
    }
    return response()->json([
      'statusinfo' => $statusinfo,
      // 'orderId' => $orderId,
      // 'fields' => $fields
    ]);
  }
  public function downloadQuotation(
    $id,
    QuotationService $quotationService,
    CreateWordDocxService $wordService,
    QuotationItemService $quotationItems,
    QuotationChargeService $quotationCharges,
    QuotationTermService $quoteTermService,
    QuotationOptionalService $quotationOptionalService,
  ) {

    $quotation = $quotationService->getQuote($id);
    $quotationItems = $quotationItems->getQuotionItemData($id);
    $quotationCharges = $quotationCharges->getQuotionCharge($id);
    $quotationTerms = $quoteTermService->getQuotationTerms($id);
    $optionalItems = $quotationOptionalService->quotationOptionalItem($id);

    $wordService->generatepdf(
      $quotation,
      $quotationItems,
      $quotationCharges,
      $quotationTerms,
      $optionalItems
    );
    //$wordService->sample();
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function winningQuotations()
  {

    return view('quotation.won');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function allQuotations()
  {
    return view('quotation.listall');
  }

  public function setReminder(Request $request, QuotationService $quotationService)
  {
    $data = $request->all();

    $id = $data['quotation_id'];

    $quotation = $quotationService->getQuote($id);


    $quotationService->updateQuote($quotation, $data);

    return response()->json(['success' => true]);
  }

  public function myReminderCalender(Request $request)
  {
    return view('quotation.mycalender');
  }

  public function updateWinDate(
    Request $request,
    QuotationService $quotationService,
    QuoteHistoryService $quoteHistoryService
  ) {
    $input = $request->all();

    $input['status_id'] = 6;
    $id = $input['quotation_id'];

    // update quotation table
    $quotation = $quotationService->getQuote($id);
    $quotationService->updateQuote($quotation, $input);

    // update quotation history table
    // if (isset($input['updated_at']) && $input['updated_at']) {
    //     $quotation->updated_at = strtotime($input['updated_at']);
    //     $quotation->save(['timestamps' => false]);
    // }
    // $quoteHistoryService->updateHistory($input, $id);

    return redirect()->back()
      ->with('success', 'Winning Date updated successfully');
  }


  public function deleteSalesCommission(Request $request, QuotationService $quotationService)
  {
    $data =  $request->all();

    if (!empty($data) && isset($data['quotation_id']) && isset($data['commision_id'])) {
      // delete commision
      $quotationService->deleteSalesCommission($data['commision_id']);

      /************************************************
       * Revise the Sales commision of Assigned manager
       *********************************/
      // get quotation
      $quotationid = $data['quotation_id'];
      $quotation = $quotationService->getQuote($quotationid);
      // get all sales commisions
      $salesComm = $quotationService->getSalesCommission($quotationid);
      // Get other manager commissions
      $scommissions = $quotationService->otherManagerCommissions($quotation);

      $commission = 0;
      $percent = 0;
      if (!empty($scommissions)) {
        $commission = $scommissions->commission_amt;
        $percent = $scommissions->percentage;
      }
      $fields = [];
      // correct/ check margins - assigned manager
      foreach ($salesComm as  $comm) {
        $balance = 0;
        $per = 0;
        if ($comm->manager_id == $quotation->assigned_to) {
          $balance = ($quotation->gross_margin - $commission);
          $per = (100 - $percent);
          $fields[] = [
            'manager_id' => $comm->manager_id,
            'percent' => (int) $per,
            'commission_amount' => $balance
          ];
        } else {
          continue;
        }
      }
      // UPDATE COMMISSION
      $quotationService->createOrUpdateCommision($fields, $quotationid);

      return response()->json(['success' => true]);
    }
  }

  public function bulkUpdateQuotations(QuotationService $quotationService)
  {
    /***********************************************
     * Update Sales Commision - Calculation
     ***********************************************/
    $quotationService->updateAllCommissions();


    /***********************************************
     * Update Win Date - Calculation
     ***********************************************/

    //$quotationService->updateAllWinDate();

    echo "Success";

    //   return response()->with('success', 'Quote updated successfully');
  }
  public function fetchChargeNames(Request $request)
  {

    $searchTerm = $request->input('term');
    $results = QuotationField::where('title', 'like', $searchTerm . '%')->get();
    return response()->json($results);
  }
  public function fetchProductModels(Request $request)
  {

    //$category = $request->input('category');
    $input = $request->all();
    $supplier_ids = is_array($input['supplier_id']) ? $input['supplier_id'] : (array) $input['supplier_id'];

    $results = Product::with('supplier')
      ->where('status', 'active')
      ->whereIn('brand_id', $supplier_ids)
      ->whereNull('deleted_at')
      ->orderBy('title', 'asc')
      ->get()
      ->groupBy('supplier.brand');

    return response()->json($results);
  }

  public function deleteItem($itemId)
  {
    $item = QuotationItem::find($itemId);

    if ($item) {
      $item->delete();
      return response()->json(['message' => 'Item deleted successfully']);
    }

    return response()->json(['message' => 'Item not found'], 404);
  }

  public function deleteCharge($chargeId)
  {

    $charge = QuotationCharge::find($chargeId);

    if ($charge) {
      $charge->delete();
      return response()->json(['message' => 'Charge deleted successfully']);
    }

    return response()->json(['message' => 'Charge not found'], 404);
  }

  public function deleteTerm($termId)
  {
    $term = QuotationTerm::find($termId);

    if ($term) {
      $term->delete();
      return response()->json(['message' => 'Term deleted successfully']);
    }

    return response()->json(['message' => 'Term not found'], 404);
  }
  public function currencyConversion(Request $request)
  {

    $selectedModelData = $request->input('selectedModelData');
    $quoteCurrency = $request->input('quoteCurrency');

    // $conversionToRate = DB::table('currency_conversion')
    // ->where('currency',$productCurrency)
    // ->select('standard_rate')
    // ->first();


    // Fetch the conversion rate from AED to the quote currency
    $conversionFromRate = DB::table('currency_conversion')
      ->where('currency', $quoteCurrency)
      ->select('standard_rate')
      ->first();



    // Check if both conversion rates are found
    // if($productCurrency !='aed'){
    //
    //   // Convert the price to AED
    //   $unitPriceInQuoteCurrencyAED = CurrencyConverter::convertedTo(
    //     $selectedModelData,
    //     $conversionToRate
    //   );
    //
    // }else{
    //
    //   $unitPriceInQuoteCurrencyAED =  $selectedModelData;
    //
    // }

    // Convert the price from AED to the quote currency
    $unitPriceInQuoteCurrency = CurrencyConverter::convertedFrom(
      $selectedModelData,
      $conversionFromRate
    );
    if ($quoteCurrency == 'aed') {
      $unitPriceInQuoteCurrency = $selectedModelData;
    } else {
      $unitPriceInQuoteCurrency = CurrencyConverter::convertedFrom(
        $selectedModelData,
        $conversionFromRate
      );
    }

    return response()->json([
      'unitPriceInQuoteCurrency' => $unitPriceInQuoteCurrency,
    ]);
  }

  public function downloadOrder(
    Request $request,
    $id,
    CreateOrderPdfService $orderPdfService,
    QuotationService $quotationService,
    QuotationChargeService $quotationChargeService,
    QuotationItemService $quotationItemService,
    QuotationTermService  $quotationTermService,
  ) {
    $statusinfo = json_decode($request->input('statusinfo'));

    $fields = json_decode($request->input('fields'));
    $orders =   $fields[0];
    $quotationId = $statusinfo->quotation_id;
    $quotationData = $quotationService->getQuote($quotationId);
    $quotationItems = $quotationItemService->getQuotionItem($quotationId);

    $quotationCharge = $quotationChargeService->getQuotionCharge($quotationId);

    $quotationTerms = $quotationTermService->getQuotionTerm($quotationId);


    $orderData = Orders::where('yespo_no', $orders->yespo_no)->first();
    $paymentTerms = PaymentTerm::where('parent_id', 0)->where('isdefault', 1)->get();
    $deliveryTerms = DB::table('delivery_terms') // Assuming 'quotation_terms' is your table name
      ->where('quotation_id', $quotationId)
      ->first();
    $paymentCycles = $quotationChargeService->getPaymentCyclesList();


    $orderPdfService->generatepdf($quotationTerms, $quotationData, $paymentCycles, $deliveryTerms, $orderData, $paymentTerms, $quotationCharge, $quotationItems);
  }

  public function getProductDetails($id)
  {

    $productHistory = ProductPriceHistory::leftJoin('users', 'product_price_histories.edited_by', '=', 'users.id')
      ->leftJoin('products', 'product_price_histories.product_id', '=', 'products.id')
      ->where('product_price_histories.product_id', $id)
      ->select(
        'product_price_histories.*',
        'users.name as user_name',
        'products.title as product_title',
        'products.modelno as modelno',
        'products.part_number',
        'products.brand_id',
        'products.allowed_discount as product_discount',
      )->orderBy('product_price_histories.created_at', 'desc')
      ->take(3)
      ->get();

    return $productHistory;
  }

  public function saveProductHistory(Request $request)
  {

    try {

      $data = $request->all();

      $product = Product::find($data['productId']);

      $today = date('Y-m-d');
      $todate = date('Y-m-d', strtotime('+1 month', strtotime($today)));

      $historyInsert = [
        'product_id' => $data['productId'],
        'selling_price' => $data['selling_price'],
        'margin_price' => $data['margin_price'],
        'margin_percent' => $data['margin_percentage'],
        'price_valid_from' => $today,
        'price_valid_to'  =>  $todate,
        'currency' => $data['quote_currency'],
        'price_basis' => $data['price_basis'],
        'edited_by' => Auth::id(),
      ];

      ProductPriceHistory::create($historyInsert);

      $pUpdate = [
        'selling_price' => $data['selling_price'],
        'margin_price' => $data['margin_price'],
        'margin_percent' => $data['margin_percentage'],
        'currency' => $data['quote_currency'],
        'price_basis' => $data['price_basis'],
        'price_valid_from' => $today,
        'price_valid_to'  =>  $todate,
        'edited_by' => Auth::id(),
      ];

      $product->update($pUpdate);

      return response()->json([
        'success' => true,
        'message' => 'Product history saved successfully',
        'product' => $product  // Include the updated product in the response
      ], 200);
    } catch (\Exception $e) {
      // Error occurred while saving product history
      return response()->json(['success' => false, 'error' => 'Failed to save product history'], 500);
    }
  }

  public function fetchEditModels($id, Request $request)
  {

    $selectedCategories = $request->selectedCategory;
    $selectedSuppliers = $request->selectedSupplier;
    $data = [];

    if (isset($selectedCategories) && !empty($selectedCategories)) {
      foreach ($selectedCategories as $categoryGroup) {
        $flattenedSuppliers = array_merge([], ...$selectedSuppliers);

        $models = Product::leftJoin('suppliers', 'products.brand_id', '=', 'suppliers.id')
          ->where('products.product_category', $categoryGroup)
          ->whereIn('products.brand_id', $flattenedSuppliers)
          ->select('products.*', 'suppliers.brand as supplier_name')
          ->get();

        $modelsGroupedBySupplier = $models->groupBy('supplier_name')->map(function ($supplierModels) {
          return $supplierModels->map(function ($model) {
            return [
              'id' => $model->id,
              'modelno' => $model->modelno,
              'supplier_name' => $model->supplier_name,
              'selling_price' => $model->selling_price,
              'margin_price' => $model->margin_price,
              'product_type' => $model->product_type,
              'supplier_id' => $model->brand_id,
              'margin_percent' => $model->margin_percent,
              'allowed_discount' => $model->allowed_discount,
              'product_category' => $model->product_category,
              'currency' => $model->currency,
              'description' => $model->description,
              'title' => $model->title,
            ];
          });
        });

        // Add the category data to the main data array, grouped by supplier
        $data[] = [
          'category' => $categoryGroup,
          'models' => $modelsGroupedBySupplier->toArray(),
        ];
      }
    }

    return response()->json($data);
  }
  public function fetchDeliveryDropdown($id, Request $request)
  {

    $paymentSubList = PaymentTerm::where('parent_id', $id)->get();
    return response()->json($paymentSubList);
  }
  public function quotationStatusList(QuotationService $quotationService)
  {

    $quotationList = $quotationService->quotationStatus();

    return view('quotation-status.quotationStatus', compact('quotationList'));
  }
}
