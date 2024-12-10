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

class ExpenseController extends Controller
{
  public function index()
  {

    $expenseData = Stock::orderBy('created_at', 'desc')->where('purchase_mode','other')->paginate(25);
    return view('expense.expense', compact(
      'expenseData',
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

    return view('expense._expense_create', compact(
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
    $input =  $request->all();

    $stock = $stockService->insertStock($input);

    return redirect()->route('expense.index')->with('success', 'Stock created successfully');
  }

  public function edit(
    int $id,
    StockService $stockService,
    EmployeeService $employeeService,
    ProductService $productService,
    DivisionService $divisionService,
    SupplierService $supplierService
  ) {

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

    return view('expense._expense_edit', compact(
      'stock',
      'stockSuppliers',
      'stockPayments',
      'stockCharges',
      'stockItems',
      'suppliers',
      'currencies',
      'terms',
      'employees',
      'divisions',
      'managers',
    ));
  }
  public function update(Request $request, $id, StockService $stockService)
  {
    $userData = $request->all();

    $stockService->updateStock($id, $userData);
    return redirect()->route('expense.index')->with('success', 'Stock updated successfully');
  }


  public function downloadExpenseOS($id, StockService $stockService)
  {

    $stockService = $stockService->getStock($id);

    $stockItems = StockItem::where('stock_id',  $stockService->id)->get();
    $stockPayments = StockPayment::where('stock_id',  $stockService->id)->get();
    $stockSuppliers = StockSupplier::where('stock_id',  $stockService->id)->first();
    $stockCharges = StockCharge::where('stock_id',  $stockService->id)->get();


    $body = view('expense.expense_summary')
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
      'format' => 'A4',
      'setAutoBottomMargin' => 'stretch',
      'setAutoTopMargin' => 'stretch',
      'useOddEven' => 1,
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
    $mpdf->WriteHTML($body);
    $mpdf->Output('OS-' . $stockService->os_number . '.pdf', 'I');
  }
}
