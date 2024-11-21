<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\SupplierService;
use App\Services\DivisionService;
use App\Services\EmployeeService;
use Image;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentTerm;
use App\Models\ProductPriceHistory;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Imports\BuyingPriceImport;
use App\Models\Currency;
use App\Models\BuyingPrice;
use Carbon\Carbon;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request, ProductService $productService, SupplierService $supplierService)
  {

    $input = $request->all();
    $products = $productService->getAllProduct($input);
    $suppliers = $supplierService->getAllSupplier();
    return view('products.index', compact('products', 'suppliers'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(
    SupplierService $supplierService,
    DivisionService $divisionService,
    EmployeeService $employeeService,
    ProductService $productService
  ) {
    $suppliers = $supplierService->getAllSupplier();
    $divisions = $divisionService->getDivisionList();
    $managers = $productService->employeesList();
    $paymentTerms = PaymentTerm::where('parent_id', 0)->orderByDesc('isdefault')->get();
    $currencies = Currency::orderBy('code', 'asc')->get();
    $currencyConversions = DB::table('currency_conversion')->get();

    return view('products.create',  compact(
      'suppliers',
      'divisions',
      'managers',
      'paymentTerms',
      'currencyConversions',
      'currencies'

    ));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, ProductService $productService)
  {

    $data = $request->all();


    $product_image = $productService->uploadImage($request);
    $product = $productService->createProduct($data, $product_image);

    return redirect()->route('products.index')->with('success', 'Product created successfully');
  }

  public function saveAjaxProduct(Request $request, ProductService $productService)
  {

    $data = $request->all();

    $product_image = $productService->uploadImage($request);
    $product = $productService->createProduct($data, $product_image);
    $product->supplier->brand = ($product->brand_id && $product->supplier) ? $product->supplier->brand : '';

    return response()->json(['success' => 'Product created successfully', 'data' => $product]);
  }
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(
    $id,
    ProductService $productService,
    SupplierService $supplierService,
    DivisionService $divisionService,
    EmployeeService $employeeService
  ) {
    $product = $productService->getProduct($id);
    $suppliers = $supplierService->getAllSupplier();
    $divisions = $divisionService->getDivisionList();
    $managers = $productService->employeesList();
    $paymentTerms = PaymentTerm::where('parent_id', 0)->get();
    $currencies = Currency::orderBy('code', 'asc')->get();
    $currencyConversions = DB::table('currency_conversion')->get();
    $productPriceHistory = ProductPriceHistory::where('product_id', $id)
      ->get();
    $purchasePriceHistory = BuyingPrice::where('product_id', $product->id)->get();

    // check month diff
    if (!empty($product->buyingPrice) && isset($product->buyingPrice[0])) {
      $valid_from = $product->buyingPrice[0]->validity_from;
      $valid_to = $product->buyingPrice[0]->validity_to;
      $date1 = new Carbon($valid_from);    // start date
      $date2 = new Carbon($valid_to);  // end date
      $product->buyingPrice[0]->buyingPriceMonth = $date1->diffInMonths($date2);
    }
    return view('products.edit',  compact(
      'product',
      'suppliers',
      'divisions',
      'managers',
      'paymentTerms',
      'currencyConversions',
      'currencies',
      'productPriceHistory',
      'purchasePriceHistory'
    ));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id, ProductService $productService)
  {
    $input = $request->all();
    $product = $productService->getProduct($id);

    $image_url = null;
    if (!empty($request->file('image'))) {
      ($product->image_url) ? $productService->deleteImage($product->image_url) : '';
      $image_url = $productService->uploadImage($request);
    }
    if (isset($request->remove_image)) {
      ($product->image_url) ? $productService->deleteImage($product->image_url) : '';
    }

    $productService->updateProduct($product, $input, $image_url);

    return redirect()->back()->with('success', 'Product updated successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id, ProductService $productService)
  {
    $product = $productService->getProduct($id);

    if ($product->image_url)
      $productService->deleteImage($product->image_url);

    $productService->DeleteProduct($product);

    return redirect()->back()
      ->with('success', 'Product deleted successfully');
  }
  public function productImport(
    SupplierService $supplierService,
    DivisionService $divisionService,
    EmployeeService $employeeService,
    ProductService $productService
  ) {
    $suppliers = $supplierService->getAllSupplier();
    $divisions = $divisionService->getDivisionList();
    $managers = $productService->employeesList();
    $currencies = Currency::all();
    return view('products.product-import', compact('suppliers', 'divisions', 'managers', 'currencies'));
  }
  public function importSave(Request $request)
  {

    $divisionId = $request->input('division_id');
    $brandId = $request->input('brand_id');
    $managerId = $request->input('manager_id');

    $file = $request->file('file');
    Excel::import(new ProductsImport($divisionId, $brandId, $managerId), $file);

    return redirect()->back()->with('success', 'Data imported successfully!');
  }
  public function downloadExcelTemplate()
  {
    return Excel::download(new ProductsExport, 'products_template.xlsx');
  }

  public function buyingPriceSave(Request $request)
  {
    $brandId = $request->input('brand_id');
    $currency = $request->input('currency');

    $file = $request->file('files');
    try {
      Excel::import(new BuyingPriceImport($brandId, $currency), $file);
      return redirect()->back()->with('success', 'Data imported successfully!');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
    }
  }

  public function saveAjaxBuyingPrice(
    Request $request,
    ProductService $productService
  ) {

    $data = $request->all();

    $buyingPrice = $productService->createBuyingPrice($data);

    return response()->json(['success' => 'Buying Price created successfully', 'data' => $buyingPrice]);
  }

  public function buyingPriceByProduct(
    Request $request,
    ProductService $productService
  ) {

    $data = $request->all();
    $id = $data['product_id'];

    $buying_price = $productService->buyingPriceByProduct($id);
    $buyingPrice = 0;

    if (!empty($buying_price)) {
      $bprice = $buying_price->buying_price;
      $bcurrency = $buying_price->buying_currency;

      $currency_rate = DB::table('currency_conversion')->where('currency', $bcurrency)->first();

      if ($currency_rate) {
        $buyingPrice = $bprice * $currency_rate->standard_rate;
        $buyingPrice = number_format((float)$buyingPrice, 2, '.', '');
      }
    }


    return response()->json(['success' => 'Buying Price fetch successfully', 'data' => $buyingPrice]);
  }
}
