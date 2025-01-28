<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\PaginateResource;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Country;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Area;
use App\Models\EmployeeArea;
use Illuminate\Support\Facades\Config;

class CrmController extends Controller {

    private $imgUrl;

    public function __construct() {
        $this->imgUrl = ((env('CDN_ENABLED', false)) ? Config::get('filesystems.disks.s3.url') : Config::get('filesystems.disks.public.url')) . '/';
    }

    public function employees() {
        $roleNames = ['salesmanager', 'coordinators', 'satellite'];
        $roles = \Spatie\Permission\Models\Role::whereIn('name', $roleNames)
                ->with(['users' => function ($query) {
                        $query->where('id', '<>', auth('sanctum')->user()->id)
                        ->orderBy('name');
                    }, 'users.employee'])
                ->get();
        $groupedByRole = $roles->mapWithKeys(function ($role) {
            return [
        $role->name => $role->users->map(function ($user) {
            return [
        'id' => $user->id,
        'name' => $user->name,
        'image_url' => cdn($user->employee->image_url)
            ];
        })
            ];
        });

        return successResponse(trans('api.success'), $groupedByRole);
    }

    public function seekAssists() {
        $roleNames = ['salesmanager', 'satellite'];

        $groupedByRole = \App\Models\User::select('users.id', 'users.name', 'employees.image_url as pimg')
                ->join('employees', 'employees.user_id', 'users.id')
                ->whereHas('roles', function ($query) use ($roleNames) {
                    $query->whereIn('name', $roleNames);
                })
                ->where('users.id', '<>', auth('sanctum')->user()->id)
                ->get();

        return successResponse(trans('api.success'), $groupedByRole);
    }

    public function brands(Request $request, $module) {
        $brands = Supplier::where('status', 1);
        switch ($module) {
            case 'demo':
                $brands->select('id', 'brand', \DB::raw("CASE WHEN logo_url IS NOT NULL  AND logo_url != '' THEN CONCAT('$this->imgUrl', logo_url) ELSE NULL END as logo"));
                if (!empty($request->search_text)) {
                    $brands->where('brand', 'LIKE', "%{$request->search_text}%");
                }
                $brands = new PaginateResource($brands->paginate($this->paginateNumber));
                break;
            case 'meeting':
                if (!empty($request->search_text)) {
                    $brands->where('brand', 'LIKE', "%{$request->search_text}%");
                }
                $brands = $brands->select('id', 'brand')
                        ->get();
                break;
        }

        return successResponse(trans('api.success'), $brands);
    }

    public function products(Request $request, $module, $brand_id = null) {
        $products = Product::where('products.status', 'active')
                ->where('product_category', 'products')
                ->orderBy('products.title', 'ASC');

        switch ($module) {
            case 'demo':
                $products->select('products.id', \DB::raw("REPLACE(REPLACE(cm_products.title, '\\t', ''), '\\n', ' ') AS title"), \DB::raw("CASE WHEN cm_products.image_url IS NOT NULL AND cm_products.image_url != '' THEN CONCAT('$this->imgUrl', cm_products.image_url) ELSE NULL END as image"), 'c.brand')
                        ->leftJoin('suppliers as c', 'products.brand_id', '=', 'c.id')
                        ->where('products.is_demo', 1);
                if (!empty($brand_id)) {
                    $products->where('products.brand_id', $brand_id);
                }
                $products = new PaginateResource($products->paginate($this->paginateNumber));
                break;
            case 'meeting':
                $products->select('products.id', \DB::raw("REPLACE(REPLACE(cm_products.title, '\\t', ''), '\\n', ' ') AS title"))
                        ->where('products.brand_id', $brand_id)
                        ->where(function ($qry) use ($request) {
                            $qry->where('products.modelno', 'like', '%' . $request->search_text . '%');
                            $qry->orWhere('products.part_number', 'like', '%' . $request->search_text . '%');
                            $qry->orWhere('products.title', 'like', '%' . $request->search_text . '%');
                        });
                $products = new PaginateResource($products->paginate($this->paginateNumber));
                break;
        }
        return successResponse(trans('api.success'), $products);
    }

    public function productDetails($id) {
        $data['product'] = Product::select(
                        'products.title',
                        'products.modelno',
                        'products.part_number',
                        'products.description',
                        'products.image_url',
                        's.brand',
                        'co.name as country'
                )
                ->leftJoin('suppliers as s', 'products.brand_id', '=', 's.id')
                ->leftJoin('countries as co', 's.country_id', '=', 'co.id')
                ->where('products.id', '=', $id)
                ->first();
        if ($data['product']) {
            $data['product']->image_url = cdn($data['product']->image_url);
            return successResponse(trans('api.success'), $data);
        }
        return errorResponse(trans('api.no_data'));
    }

    public function countries() {
        $countries = Country::select('id', 'name')
                ->where('status', 1)
                ->with('regions:id,country_id,state')
                ->get();
        return successResponse(trans('api.success'), $countries);
    }

    public function getAreas() {
        $areas = Area::select('id', 'name')
                ->where('status', 1)
                ->orderBy('name')
                ->get();
        return successResponse(trans('api.success'), $areas);
    }

    /* public function getAreas() {
      $areas = Area::select('id', 'name')
      ->with(['users' => function ($query) {
      $query->select('users.id', 'users.name', 'users.email', 'employees.image_url as pimg', 'employees.division')
      ->join('employees', 'employees.user_id', '=', 'users.id');
      }])
      ->where('status', 1)
      ->orderBy('name')
      ->get();
      return successResponse(trans('api.success'), $areas);
      } */

    public function getAreaManager($areaId, $division) {
        $data['manager'] = EmployeeArea::select('users.id', 'users.name', 'employees.image_url')
                ->join('users', 'users.id', 'employee_areas.user_id')
                ->join('employees', 'employees.user_id', 'employee_areas.user_id')
                ->where('employee_areas.id', $areaId)
                ->where('employees.division', $division)
                ->first();
        if ($data['manager']) {
            $data['manager']->image_url = cdn($data['manager']->image_url);
        }
        return successResponse(trans('api.success'), $data);
    }

    public function createCompany(Request $request) {
        $rules = [
            'company' => 'required',
            'country_id' => 'required',
            'region_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }
        $request['company'] = trim($request->company);
        $exist = Company::where('company', 'like', $request->company)
                ->where('status', 1)
                ->first();
        if ($exist) {
            return errorResponse(trans('api.company_exist'));
        }

        $lastId = Company::max('id');
        $lastId = ($lastId) ? $lastId : 0;
        $request['reference_no'] = "YES/COM/" . ($lastId + 1);
        $company = Company::create($request->all());
        return successResponse(trans('api.success'), $company);
    }

    public function createCustomer(Request $request) {
        $rules = [
            'fullname' => 'required',
            'company_id' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }
        $request['fullname'] = trim($request->fullname);
        $exist = Customer::where('company_id', $request->company_id)
                ->where('fullname', 'like', $request->fullname)
                ->where('status', 1)
                ->first();
        if ($exist) {
            return errorResponse(trans('api.customer_exist'));
        }

        $customer = Customer::create($request->all());
        return successResponse(trans('api.success'), $customer);
    }

    public function privacyPolicy() {
        $page = \App\Models\Page::whereSlug('privacy-policy')->first();
        return successResponse(trans('api.success'), $page);
    }
}
