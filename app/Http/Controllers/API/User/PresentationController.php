<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PaginateResource;

class PresentationController extends Controller {

    private $yc_url;
    private $rf_url;
    private $ym_url;

    public function __construct() {
        $this->ym_url = env('YM_IMG_URL', 'https://yesmachinery.bigleap.tech/storage/');
        $this->yc_url = env('YC_IMG_URL', 'https://www.admin.yesclean.ae/storage/');
        $this->rf_url = env('RF_IMG_URL', 'https://www.cms.rhinofloor.ae/storage/');
    }

    public function divisions() {
        //\DB::raw('"ym" as type')
        $divisions = \DB::connection('yesmachine')
                ->table('divisions as d')
                ->select('d.name', 'd.code', \DB::raw('COUNT(ym_s.id) as brand_count'))
                ->leftJoin('suppliers as s', function ($join) {
                    $join->on('d.code', '=', 's.division')
                    ->where('s.status', '=', 1);
                })
                ->where('d.status', 1)
                ->groupBy('d.name', 'd.code')
                ->get();

        $staticDivisions = collect([
            [
                "name" => 'Yes Clean',
                "code" => 'ST-YC',
                "brand_count" => $this->getBrnadCount('ST-YC')
            ],
            [
                "name" => 'Rhino Floor',
                "code" => 'ST-RF',
                "brand_count" => $this->getBrnadCount('ST-RF')
            ],
        ]);

        $divisions = $divisions->merge($staticDivisions);

        return successResponse(trans('api.success'), $divisions);
    }

    public function brands($division) {
        switch ($division) {
            case 'ST-YC':
                $brands = \DB::connection('yesclean')
                        ->table('suppliers');
                $imgUrl = $this->yc_url;
                break;
            case 'ST-RF':
                $brands = \DB::connection('rhinofloor')
                        ->table('suppliers');
                $imgUrl = $this->rf_url;
                break;
            default:
                $brands = \DB::connection('yesmachine')
                        ->table('suppliers')
                        ->where('division', $division);
                $imgUrl = $this->ym_url;
        }

        $brands->select('id', 'brand', \DB::raw("CONCAT('$imgUrl', logo_url) as logo"))->where('status', 1);
        $brands = new PaginateResource($brands->paginate(10));
        /* $brands->getCollection()->transform(function ($brand) use ($imgUrl) {
          $brand->logo_url = $imgUrl . '/' . $brand->logo_url;
          return $brand;
          }); */
        return successResponse(trans('api.success'), $brands);
    }

    function getBrnadCount($type) {
        switch ($type) {
            case 'ST-YC':
                $brands = \DB::connection('yesclean')->table('suppliers')->where('status', 1)->count();
                break;
            case 'ST-RF':
                $brands = \DB::connection('rhinofloor')->table('suppliers')->where('status', 1)->count();
                break;
            default:
                $brands = 0;
        }
        return $brands;
    }

    public function products($division, $brand_id = null) {
        switch ($division) {
            case 'ST-YC':
                $brands = \DB::connection('yesclean');
                $imgUrl = \DB::raw("CONCAT('$this->yc_url', p.default_image) as image");
                break;
            case 'ST-RF':
                $brands = \DB::connection('rhinofloor');
                $imgUrl = \DB::raw("CONCAT('$this->rf_url', p.default_image) as image");
                break;
            default:
                $brands = \DB::connection('yesmachine');
                $imgUrl = \DB::raw("CONCAT('$this->ym_url', ym_p.default_image) as image");
        }
        $brands = $brands->table('products as p')
                ->select('p.id', 'p.name', $imgUrl, 'c.name as category')
                ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
                ->where('p.status', 1)
                ->orderBy('p.name', 'ASC');
        if (!empty($brand_id)) {
            $brands->where('p.brand_id', $brand_id);
        }
        return successResponse(trans('api.success'), new PaginateResource($brands->paginate(10)));
    }

    public function productDetails($division, $id) {
        switch ($division) {
            case 'ST-YC':
                $db = \DB::connection('yesclean');
                $data['image_url'] = $this->yc_ur;
                $share_url = env('YC_SHARE_URL', 'https://www.yesclean.ae/');
                break;
            case 'ST-RF':
                $db = \DB::connection('rhinofloor');
                $data['image_url'] = $this->rf_url;
                $share_url = env('RF_SHARE_URL', 'https://www.rhinofloor.ae/');
                break;
            default:
                $db = \DB::connection('yesmachine');
                $data['image_url'] = $this->ym_url;
                $share_url = env('YM_SHARE_URL', 'https://yeswebsite.bigleap.tech/');
        }
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
