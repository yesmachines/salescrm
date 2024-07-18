<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use DB;
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


      $image->save(public_path('storage/' . $imageUrl));


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
      'image_url' => $imageUrl,
      'price_valid_from' => $data['start_date'],
      'price_valid_to' => $data['end_date'],
      'product_category' => $data['product_category'],
      'status' => $data['status'],
      'price_basis' => $data['payment_term'],
      'currency' => $data['currency'],
      //   'currency_rate' => $data['currency_rate'],

    ]);
    ProductPriceHistory::create([

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

    return $product;
  }
  public function getAllProduct($data = []): Object
  {
      $searchTerm = isset($data['query']) ? $data['query'] : null;
      $brandId = isset($data['brand_id']) ? $data['brand_id'] : null;

      $products = Product::where('status', 'active')->orderBy('created_at', 'desc');

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

    $product->delete();
  }

  public function updateProduct(Product $product, array $data, string $imageUrl = null): void
  {
    $oldProduct = new Product($product->only(['selling_price', 'margin_price', 'price_valid_from', 'price_valid_to']));
    $data['selling_price'] = str_replace(',', '', $data['selling_price']);
    $data['margin_price'] = str_replace(',', '', $data['margin_price']);
    $update = [
      'title' => $data['title'],
      'description' => $data['description'],
      'modelno' => $data['model_no'],
      'part_number' => $data['part_number'],
      'brand_id' => $data['brand_id'],
      'division_id' => $data['division_id'],
      'product_type' => $data['product_type'],
      'manager_id' => $data['manager_id'],
      'selling_price' => $data['selling_price'],
      'margin_percent' => $data['margin_percentage'],
      'margin_price' => $data['margin_price'],
      'allowed_discount' => $data['allowed_discount'],
      'freeze_discount' => $data['freeze_discount'] ?? 0,
      'price_valid_from' => $data['start_date'],
      'price_valid_to' => $data['end_date'],
      'product_category' => $data['product_category'],
      'status' => $data['status'],
      'price_basis' => $data['payment_term'],
      'currency' => $data['currency'],
      //   'currency_rate' => $data['currency_rate'],


    ];

    if (!empty($imageUrl) ||  isset($data['remove_image'])) {

      $update['image_url'] = $imageUrl;
    }


    $product->update($update);
    $changes = $this->getChanges($oldProduct->getAttributes(), $product->only(['selling_price', 'margin_price', 'price_valid_from', 'price_valid_to']));

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
  }
  public function employeesList($currentid = 0): Object
  {


    $authorizedRoles = ['divisionmanager', 'salesmanager', 'coordinators','admin'];

    $sql = Employee::with('user')->with('user.roles')->whereHas('user.roles', function ($query) use ($authorizedRoles) {
      $query->whereIn('name', $authorizedRoles);
    });

    if (isset($data['status']) && !empty($data['status'])) {
      $sql->where('status', '=', $data['status']);
    }
    $employees = $sql->get();

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
