<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PaginateResource;
use App\Models\Supplier;
use App\Models\Product;

class CrmController extends Controller {

    private $imgUrl;

    public function __construct() {
        $this->imgUrl = asset('storage') . '/';
    }

    public function brands($module) {
        $brands = Supplier::where('status', 1);
        switch ($module) {
            case 'demo':
                $brands->select('id', 'brand', \DB::raw("CASE WHEN logo_url IS NOT NULL  AND logo_url != '' THEN CONCAT('$this->imgUrl', logo_url) ELSE NULL END as logo"));
                $brands = new PaginateResource($brands->paginate(10));
                break;
            case 'meeting':
                $brands = $brands->select('id', 'brand')
                        ->get();
                break;
        }

        return successResponse(trans('api.success'), $brands);
    }

    public function products($module, $brand_id = null) {
        $products = Product::where('products.status', 'active')
                ->where('product_category','products')
                ->orderBy('products.title', 'ASC');

        switch ($module) {
            case 'demo':
                $products->select('products.id',  \DB::raw("REPLACE(REPLACE(cm_products.title, '\\t', ''), '\\n', ' ') AS title"), \DB::raw("CASE WHEN cm_products.image_url IS NOT NULL AND cm_products.image_url != '' THEN CONCAT('$this->imgUrl', cm_products.image_url) ELSE NULL END as image"), 'c.brand')
                        ->leftJoin('suppliers as c', 'products.brand_id', '=', 'c.id')
                        ->where('products.is_demo', 1);
                if (!empty($brand_id)) {
                    $products->where('products.brand_id', $brand_id);
                }
                $products = new PaginateResource($products->paginate(10));
                break;
            case 'meeting':
                $products->select('products.id', \DB::raw("REPLACE(REPLACE(cm_products.title, '\\t', ''), '\\n', ' ') AS title"));
                if (!empty($brand_id)) {
                    $products->where('products.brand_id', $brand_id);
                }
                $products = $products->get();
                break;
        }
        return successResponse(trans('api.success'), $products);
    }

}