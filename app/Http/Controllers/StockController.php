<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\PaymentTerm;
use App\Models\Stock;
use App\Models\StockItem;
use App\Services\StockService;
use App\Models\StockPayment;
use App\Models\StockSupplier;
use App\Models\StockCharge;
use App\Services\DivisionService;
use App\Services\EmployeeService;
use App\Services\ProductService;
use App\Services\SupplierService;
use DB;

class StockController extends Controller
{
  public function index()
  {

    $stocks = Stock::orderBy('created_at', 'desc')->paginate(25);
    return view('stock.stock', compact(
      'stocks',
    ));
  }
  public function create(
    EmployeeService $employeeService,
    DivisionService $divisionService,
    ProductService $productService,
    SupplierService $supplierService
  ) {

    $suppliers = $supplierService->getAllSupplier();
    $currencies = DB::table('currency')->where('status', 1)->orderBy("code", "asc")->get();
    $terms = PaymentTerm::where("status", 1)->where("parent_id", 0)->get();
    $employees = $employeeService->getAllEmployee();
    $divisions = $divisionService->getDivisionList();
    $managers = $productService->employeesList();
    // $paymentTerms =  PaymentTerm::where('parent_id', 0)->get();

    return view('stock._stock_create', compact(
      'suppliers',
      'currencies',
      'terms',
      'employees',
      'divisions',
      'managers',
      //  'paymentTerms'
    ));
  }

  public function store(Request $request, StockService $stockService)
  {

    $this->validate($request, [
      'assigned_to'       => 'required',
      'supplier_id'       => 'required',
      'price_basis'       => 'required',
      'delivery_term'     => 'required',
      'item_name.*'       => 'required',
      'quantity.*'        => 'required|numeric|min:1',
      'total_amount.*'    => 'required',
      'total_buying_price' => 'required',
      'payment_term.*'     => 'required',
    ]);

    $input =  $request->all();

    // create
    $stock = $stockService->insertStock($input);

    return redirect()->route('stock.index')->with('success', 'Stock created successfully');
  }

  public function edit(
    int $id,
    StockService $stockService,
    EmployeeService $employeeService,
    ProductService $productService,
    DivisionService $divisionService,
    SupplierService $supplierService
  ) {

    //$paymentTerms =  PaymentTerm::where('parent_id', 0)->get();

    $managers = $productService->employeesList();
    $divisions = $divisionService->getDivisionList();
    $stock = Stock::find($id);
    $suppliers = $supplierService->getAllSupplier();
    $currencies = DB::table('currency')->where('status', 1)->orderBy("code", "asc")->get();
    $terms = PaymentTerm::where("status", 1)->where("parent_id", 0)->get();
    $stockSuppliers = StockSupplier::where('stock_id', $id)->first();
    $stockPayments = StockPayment::where('stock_id', $id)->get();
    $stockCharges = StockCharge::where('stock_id', $id)->get();
    $stockItems = StockItem::where('stock_id', $id)->get();
    $employees = $employeeService->getAllEmployee();


    $products = $productService->getAllProduct(['brand_id' => $stockSuppliers->supplier_id]);

    return view('stock._stock_edit', compact(
      'stock',
      'stockSuppliers',
      'stockPayments',
      'stockCharges',
      'stockItems',
      'suppliers',
      'currencies',
      'terms',
      'employees',
      'products',
      'divisions',
      'managers',
      //'paymentTerms'
    ));
  }
  public function update(Request $request, $id, StockService $stockService)
  {
    $userData = $request->all();

    // $this->validate($request, [
    //   'assigned_to'       => 'required',
    //   'supplier_id'       => 'required',
    //   'price_basis'       => 'required',
    //   'delivery_term'     => 'required',
    //   'item_name.*'       => 'required',
    //   'quantity.*'        => 'required|numeric|min:1',
    //   'total_amount.*'    => 'required',
    //   'total_buying_price' => 'required',
    //   'payment_term.*'     => 'required',
    // ]);

    $stockService->updateStock($id, $userData);
    return redirect()->route('stock.index')->with('success', 'Stock updated successfully');
  }


  public function downloadStockOS($id, StockService $stockService)
  {

    $stockService = $stockService->getStock($id);

    $stockItems = StockItem::where('stock_id',  $stockService->id)->get();
    $stockPayments = StockPayment::where('stock_id',  $stockService->id)->get();
    $stockSuppliers = StockSupplier::where('stock_id',  $stockService->id)->first();
    $stockCharges = StockCharge::where('stock_id',  $stockService->id)->get();


    $body = view('stock.stock_summary')
      ->with(compact(
        'stockService',
        'stockItems',
        'stockPayments',
        'stockSuppliers',
        'stockCharges'
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
      'useOddEven' => 1,
      // Additional configuration for centering content horizontally
      'default_font_size' => 0,
      'default_font' => '',
      'margin_left' => 16,
      'margin_right' => 0,
      'margin_top' => 15,
    ]);

    $mpdf->useSubstitutions = true;
    $mpdf->SetWatermarkText("YESMACHINERY", 0.1);
    $mpdf->showWatermarkText = true;
    $mpdf->SetTitle('OS-' . $stockService->os_number . '.pdf');
    // $mpdf->WriteHTML($header);
    $mpdf->WriteHTML($body);
    $mpdf->Output('OS-' . $stockService->os_number . '.pdf', 'I');
  }
}
