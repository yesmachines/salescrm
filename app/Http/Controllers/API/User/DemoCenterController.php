<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PaginateResource;
use App\Models\Supplier;
use App\Models\Product;

class DemoCenterController extends Controller {

    private $imgUrl;

    public function __construct() {
        $this->imgUrl = asset('storage').'/';
    }

    public function brands() {
        $brands = Supplier::select('id', 'brand', \DB::raw("CASE WHEN logo_url IS NOT NULL  AND logo_url != '' THEN CONCAT('$this->imgUrl', logo_url) ELSE NULL END as logo"))->where('status', 1);
        $brands = new PaginateResource($brands->paginate($this->paginateNumber));
        return successResponse(trans('api.success'), $brands);
    }

    public function products($brand_id = null) {
        $products = Product::select('products.id', 'products.title', \DB::raw("CASE WHEN cm_products.image_url IS NOT NULL AND cm_products.image_url != '' THEN CONCAT('$this->imgUrl', cm_products.image_url) ELSE NULL END as image"), 'c.brand')
                ->leftJoin('suppliers as c', 'products.brand_id', '=', 'c.id')
                ->where('products.status', 'active')
                ->where('products.is_demo', 1)
                ->orderBy('products.title', 'ASC');
        if (!empty($brand_id)) {
            $products->where('products.brand_id', $brand_id);
        }
        return successResponse(trans('api.success'), new PaginateResource($products->paginate($this->paginateNumber)));
    }

    public function productDetails($division, $id) {
        $data['product'] = $db->table('products as p')
                ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
                ->leftJoin('suppliers as s', 'p.brand_id', '=', 's.id')
                ->leftJoin('countries as co', 's.country_id', '=', 'co.id')
                ->select(
                        'p.name',
                        'p.subtitle',
                        'p.slug',
                        'p.description',
                        's.brand',
                        'c.name as category',
                        'co.name as country'
                )
                ->where('p.id', '=', $id)
                ->first();
        if ($data['product']) {
            $data['product']->share_url = $share_url . $data['product']->slug;
            $data['gallery'] = $db->table('product_images as pi')
                    ->where('pi.product_id', $id)
                    ->where('pi.image_type', 'gallery')
                    ->orderBy('pi.priority', 'asc')
                    ->get();
            $data['product_images'] = $db->table('product_images as pi')
                    ->where('pi.product_id', $id)
                    ->where('pi.image_type', 'application')
                    ->orderBy('pi.priority', 'asc')
                    ->get();
            $data['product_videos'] = $db->table('product_videos as pv')
                    ->where('pv.product_id', $id)
                    ->orderBy('pv.priority', 'asc')
                    ->get();
            $data['product_catalogues'] = $db->table('product_catalogues as pc')
                    ->where('pc.product_id', $id)
                    ->where('pc.pdf_type', 'brochure')
                    ->orderBy('pc.priority', 'asc')
                    ->get();
            return successResponse(trans('api.success'), $data);
        }
        return errorResponse(trans('api.no_data'));
    }
}
