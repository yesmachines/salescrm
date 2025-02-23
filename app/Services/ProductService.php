<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\ProductPriceHistory;
use App\Models\BuyingPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use DB;
use App\Models\CustomPrice;
use Illuminate\Support\Facades\File;

class ProductService
{
  public function uploadImage(Request $request): ?string
  {
    $imageUrl = "";
    if ($request->hasFile('image')) {
      $file = $request->file('image');
      $assetName = $request->input('name') . time();
      $filename =  $assetName . '.' . $file->getClientOriginalExtension();
      $imageUrl = 'products/' . $filename;
      $productsFolderPath = storage_path('app/public/products');
      if (!File::exists($productsFolderPath)) {
        File::makeDirectory($productsFolderPath, 0775, true, true);
      }

      $image = Image::make($file);

      $image->resize(300, 300, function ($constraint) {
        $constraint->aspectRatio();
      });
      $image->save(storage_path('app/public/' . $imageUrl));


      return $imageUrl;
    }

    return null;
  }

  public function createProduct(array $data, ?string $imageUrl): Product
  {


    $data['selling_price'] = str_replace(',', '', $data['selling_price']);
    $data['margin_price'] = str_replace(',', '', $data['margin_price']);

    $productType = ($data['product_type'] == 'undefined' || $data['product_type'] == 'null' || !trim($data['product_type'])) ? 'custom' : $data['product_type'];

    $product =  Product::create([
      'title' => $data['title'],
      'description' => $data['description'],
      'modelno' => $data['model_no'],
      'part_number' => $data['part_number'],
      'brand_id' => $data['brand_id'],
      'division_id' => $data['division_id'],
      'product_type' => $productType,
      'manager_id' => $data['manager_id'],
      'selling_price' => $data['selling_price'],
      'margin_percent' => $data['margin_percentage'],
      'margin_price' => $data['margin_price'],
      'allowed_discount' => $data['allowed_discount'],
      'freeze_discount' => $data['freeze_discount'] ?? 0,
      'purchase_currency' => $data['buying_currency'],
      'purchase_price' => str_replace(',', '', $data['buying_price']),
      'image_url' => $imageUrl,
      'price_valid_from' => $data['start_date'],
      'price_valid_to' => $data['end_date'],
      'product_category' => $data['product_category'],
      'status' => $data['status'],
      'price_basis' => $data['payment_term'],
      'currency' => $data['currency'],
      //   'currency_rate' => $data['currency_rate'],
      'is_demo' => isset($data['is_demo']) ? $data['is_demo'] : 0,

    ]);
    // Selling Price
  $productPriceHistory=  ProductPriceHistory::create([
      'product_id' => $product->id,
      'selling_price' => $data['selling_price'],
      'margin_price' => $data['margin_price'],
      'price_valid_from' => $data['start_date'],
      'price_valid_to' => $data['end_date'],
      'price_basis' => $data['payment_term'],
      'margin_percent' => $data['margin_percentage'],
      'currency' => $data['currency'],
      'edited_by' => Auth::id()
    ]);
    // Buying Price
    $data['buying_price'] = str_replace(',', '', $data['buying_price']);
    $data['gross_price'] = str_replace(',', '', $data['gross_price']);
    $data['discount_amount'] = str_replace(',', '', $data['discount_amount']);

    BuyingPrice::create([
      'product_id'    => $product->id,
      'buying_price'    => $data['buying_price'],
      'buying_currency' => $data['buying_currency'],
      'gross_price'     => $data['gross_price'],
      'discount'        => $data['discount'],
      'discount_amount' => $data['discount_amount'],
      'added_by'        => Auth::id(),
      'validity_from'   => $data['validity_from'],
      'validity_to'     => $data['validity_to'],
    ]);
    CustomPrice::create([
      'product_id'    => $product->id,
      'product_history_id' => $productPriceHistory->id,
      'packing'    => isset($data['packing']) ? $data['packing'] : null,
      'road_transport_to_port' => isset($data['road_transport_to_port']) ? $data['road_transport_to_port'] : null,
      'freight'     => isset($data['freight']) ? $data['freight'] : null,
      'insurance'        => isset($data['insurance']) ? $data['insurance'] : null,
      'clearing' => isset($data['clearing']) ? $data['clearing'] : null,
      'boe'   => isset($data['boe']) ? $data['boe'] : null,
      'handling_and_local_transport'     => isset($data['handling_and_local_transport']) ? $data['handling_and_local_transport'] : null,
      'customs'     => isset($data['customs']) ? $data['customs'] : null,
      'delivery_charge'     => isset($data['delivery_charge']) ? $data['delivery_charge'] : null,
      'mofaic'     => isset($data['mofaic']) ? $data['mofaic'] : null,
      'status'     => '1',
  ]);


    return $product;
  }
  public function createBuyingPrice(array $data): BuyingPrice
  {
    return BuyingPrice::create([
      'product_id'      => $data['product_id'],
      'buying_price'    => $data['buying_price'],
      'buying_currency' => $data['buying_currency'],
      'gross_price'     => $data['gross_price'],
      'discount'        => $data['discount'],
      'discount_amount' => $data['discount_amount'],
      'added_by'        => Auth::id(),
      'validity_from'   => $data['validity_from'],
      'validity_to'     => $data['validity_to'],
    ]);
  }
  public function getAllProduct($data = []): Object
  {
    $searchTerm = isset($data['query']) ? $data['query'] : null;
    $brandId = isset($data['brand_id']) ? $data['brand_id'] : null;

    $products = Product::where('status', 'active')
      ->whereNull('deleted_at')
      ->with('buyingPrice')
      ->orderBy('created_at', 'desc');

    if ($searchTerm) {
      $products->where(function ($query) use ($searchTerm) {
        $query->where('title', 'like', "%{$searchTerm}%")
          ->orWhere('modelno', 'like', "%{$searchTerm}%")
          ->orWhere('part_number', 'like', "%{$searchTerm}%")
          ->orWhereHas('supplier', function ($query) use ($searchTerm) {
            $query->where('brand', 'like', "%{$searchTerm}%");
          });
      });
    }

    if ($brandId) {
      $products->whereHas('supplier', function ($query) use ($brandId) {
        $query->where('id', $brandId);
      });
    }

    return $products->paginate(50);
  }
  public function getProduct($id): Product
  {
    return Product::find($id);
  }
  public function buyingPriceByProduct($pid)
  {
    return BuyingPrice::where('product_id', $pid)->orderBy('id', 'desc')->first();

    //return $buyingPrice;
  }
  public function deleteImage(string $image): void
  {
    // delete image
    if ($image) {
      $image_path = storage_path('app/public/') . $image; // upload path
      if (File::exists($image_path)) {
        File::delete($image_path);
      }
    }
  }
  public function DeleteProduct(Product $product): void
  {
    $isBuyingRecords = BuyingPrice::where('product_id', $product->id);
    if ($isBuyingRecords->count() > 0) {
      $isBuyingRecords->delete();
      // delete buying prices
    }
    $product->delete();
  }

  public function updateProduct(Product $product, array $data, string $imageUrl = null): void
  {
    $oldProduct = new Product($product->only(['selling_price', 'margin_price', 'price_valid_from', 'price_valid_to']));

    if (isset($data['title'])) {
      $update['title'] = $data['title'];
    }
    if (isset($data['description'])) {
      $update['description'] = $data['description'];
    }
    if (isset($data['model_no'])) {
      $update['modelno'] = $data['model_no'];
    }
    if (isset($data['part_number'])) {
      $update['part_number'] = $data['part_number'];
    }
    if (isset($data['brand_id'])) {
      $update['brand_id'] = $data['brand_id'];
    }
    if (isset($data['division_id'])) {
      $update['division_id'] = $data['division_id'];
    }
    if (isset($data['product_type'])) {
      $update['product_type'] = $data['product_type'];
    }
    if (isset($data['manager_id'])) {
      $update['manager_id'] = $data['manager_id'];
    }
    if (isset($data['selling_price'])) {
      $update['selling_price'] = str_replace(',', '', $data['selling_price']);
    }
    if (isset($data['margin_percentage'])) {
      $update['margin_percent'] = $data['margin_percentage'];
    }
    if (isset($data['margin_price'])) {
      $update['margin_price'] = str_replace(',', '', $data['margin_price']);
    }
    if (isset($data['allowed_discount'])) {
      $update['allowed_discount'] = $data['allowed_discount'];
    }
    if (isset($data['freeze_discount'])) {
      $update['freeze_discount'] = $data['freeze_discount'] ?? 0;
    }
    if (isset($data['start_date'])) {
      $update['price_valid_from'] = $data['start_date'];
    }
    if (isset($data['end_date'])) {
      $update['price_valid_to'] = $data['end_date'];
    }
    if (isset($data['product_category'])) {
      $update['product_category'] = $data['product_category'];
    }
    if (isset($data['status'])) {
      $update['status'] = $data['status'];
    }
    if (isset($data['payment_term'])) {
      $update['price_basis'] = $data['payment_term'];
    }
    if (isset($data['currency'])) {
      $update['currency'] = $data['currency'];
    }
    if (isset($data['is_demo'])) {
      $update['is_demo'] = $data['is_demo'];
    }
    if (isset($data['buying_currency']) && !empty($data['buying_currency'])) {
      $update['purchase_currency'] = $data['buying_currency'];
    }
    if (isset($data['buying_price']) && !empty($data['buying_price'])) {
      $update['purchase_price'] = str_replace(',', '', $data['buying_price']);
    }
    if (!empty($imageUrl) ||  isset($data['remove_image'])) {
      $update['image_url'] = $imageUrl;
    }

    $product->update($update);

    // $update = [
    // 'title' => $data['title'],
    // 'description' => $data['description'],
    // 'modelno' => $data['model_no'],
    // 'part_number' => $data['part_number'],
    // 'brand_id' => $data['brand_id'],
    // 'division_id' => $data['division_id'],
    // 'product_type' => $data['product_type'],
    // 'manager_id' => $data['manager_id'],
    // 'selling_price' => $data['selling_price'],
    // 'margin_percent' => $data['margin_percentage'],
    // 'margin_price' => $data['margin_price'],
    // 'allowed_discount' => $data['allowed_discount'],
    // 'freeze_discount' => $data['freeze_discount'] ?? 0,
    // 'price_valid_from' => $data['start_date'],
    // 'price_valid_to' => $data['end_date'],
    //'product_category' => $data['product_category'],
    //'status' => $data['status'],
    //'price_basis' => $data['payment_term'],
    // 'currency' => $data['currency'],
    //   'currency_rate' => $data['currency_rate'],
    // 'is_demo' => $data['is_demo'],
    //];


    $changes = $this->getChanges(
      $oldProduct->getAttributes(),
      $product->only(['selling_price', 'margin_price', 'price_valid_from', 'price_valid_to'])
    );

    if (!empty($changes)) {
      ProductPriceHistory::create([
        'product_id' => $product->id,
        'selling_price' => $data['selling_price'],
        'margin_price' => $data['margin_price'],
        'margin_percent' => $data['margin_percentage'],
        'price_basis' => $data['payment_term'],
        'currency' => $data['currency'],
        'price_valid_from' => $data['start_date'],
        'price_valid_to' => $data['end_date'],
        'edited_by' => Auth::id()
      ]);
    }
    // Buying Price, currency, gross price
    if (isset($data['buying_price']) && isset($data['gross_price']) && isset($data['buying_currency'])) {

      $data['buying_price'] = str_replace(',', '', $data['buying_price']);
      $data['gross_price'] = str_replace(',', '', $data['gross_price']);
      $data['discount_amount'] = str_replace(',', '', $data['discount_amount']);

      $isPurchaseExist = BuyingPrice::where('product_id', $product->id)->count();
      if ($isPurchaseExist > 0) {
        $existingPurchaseRow = BuyingPrice::where('product_id', $product->id)->latest()->first();

        $isRecordChange = false;
        if (
          $existingPurchaseRow->buying_currency  !== $data['buying_currency'] ||
          $existingPurchaseRow->buying_price  !== $data['buying_price'] ||
          $existingPurchaseRow->gross_price  !== $data['gross_price'] ||
          $existingPurchaseRow->discount_amount  !== $data['discount_amount'] ||
          ($existingPurchaseRow->validity_from  !== $data['validity_from'] &&
            $existingPurchaseRow->validity_to  !== $data['validity_to'])
        ) {
          $isRecordChange = true;
        }

        // if any of the exiting record changed, please create new
        if ($isRecordChange === true) {
          BuyingPrice::create([
            'product_id'    => $product->id,
            'buying_price'    => $data['buying_price'],
            'buying_currency' => $data['buying_currency'],
            'gross_price'     => $data['gross_price'],
            'discount'        => $data['discount'],
            'discount_amount' => $data['discount_amount'],
            'added_by'        => Auth::id(),
            'validity_from'   => $data['validity_from'],
            'validity_to'     => $data['validity_to'],
          ]);
        }
      } else {
        // if no buying price  record exists
        BuyingPrice::create([
          'product_id'    => $product->id,
          'buying_price'    => $data['buying_price'],
          'buying_currency' => $data['buying_currency'],
          'gross_price'     => $data['gross_price'],
          'discount'        => $data['discount'],
          'discount_amount' => $data['discount_amount'],
          'added_by'        => Auth::id(),
          'validity_from'   => $data['validity_from'],
          'validity_to'     => $data['validity_to'],
        ]);
      }
    }
  }

  public function employeesList($currentid = 0): Object
  {


    $authorizedRoles = ['divisionmanager', 'salesmanager', 'coordinators', 'admin'];

    $sql = Employee::with('user')->with('user.roles')->whereHas('user.roles', function ($query) use ($authorizedRoles) {
      $query->whereIn('name', $authorizedRoles);
    });

    //if (isset($data['status']) && !empty($data['status'])) {
    //$sql->where('status', '=', $data['status']);
    // }
    $sql->where('status', '=', 1);
    $employees = $sql->get()->sortBy('user.name');

    return $employees;
  }

  private function getChanges(array $oldAttributes, array $newAttributes): array
  {
    $changes = [];

    foreach ($newAttributes as $key => $value) {

      $oldValue = is_numeric($oldAttributes[$key]) ? (float)$oldAttributes[$key] : $oldAttributes[$key];
      $newValue = is_numeric($value) ? (float)$value : $value;


      if ($newValue !== $oldValue) {
        $changes[$key] = [
          'old' => $oldValue,
          'new' => $newValue,
        ];
      }
    }

    return $changes;
  }
}
