<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PaginateResource;
use Validator;
use App\Enums\EnquirySource;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\LeadStatus;

class EnquiryController extends Controller {

    public function getStatus() {
        $status = LeadStatus::where('status', 1)->orderBy('priority', 'asc')->pluck('name', 'id')->toArray();
        return successResponse(trans('api.success'), $status);
    }

    public function getCompanies(Request $request) {
        $rules = [
            'search_text' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $companies = Company::select('id', 'company', 'region_id', 'country_id')
                        ->orderBy('company', 'asc')
                        ->where('company', 'LIKE', "%{$request->search_text}%")
                        ->where('status', 1)->get()->map(function ($company) {
            if ($company->region_id > 0) {
                $company->company .= ($company->region) ? ', ' . $company->region->state : '';
            }
            if ($company->country_id > 0) {
                $company->company .= ($company->country) ? ', ' . $company->country->name : '';
            }
            return [
        'id' => $company->id,
        'name' => $company->company,
            ];
        });
        return successResponse(trans('api.success'), $companies);
    }

    public function getCustomers($company_id) {
        $customers = Customer::select('id', 'fullname', 'email', 'phone')
                ->where('company_id', $company_id)
                ->where('status', 1)
                ->get();
        return successResponse(trans('api.success'), $customers);
    }

    public function getExpo() {
        $expo = \App\Models\Expo::pluck('name', 'id');
        return successResponse(trans('api.success'), $expo);
    }

    public function index(Request $request) {
        $enquiryTypes = array_column(EnquirySource::cases(), 'value');
        $rules = [
            'type' => ['required', 'in:' . implode(',', $enquiryTypes)],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $enquirySql = Lead::select('id', 'company_id', 'status_id', 'details', 'enquiry_date')
                ->with(['company:id,company', 'leadStatus:id,name'])
                ->where('assigned_to', $request->user()->id)
                ->where('lead_type', $request->type)
                ->orderBy('enquiry_date', 'DESC');

        if (!empty($request->expo_id)) {
            $enquirySql->where('expo_id', $request->expo_id);
        }

        $enquiry = new PaginateResource($enquirySql->paginate($this->paginateNumber));
        return successResponse(trans('api.success'), $enquiry);
    }

    public function show($id) {
        $enquiry = Lead::select('id', 'company_id', 'customer_id', 'status_id', 'details', 'enquiry_date', 'lead_type', 'expo_id')
                ->with(['company:id,country_id,region_id,company', 'company.country:id,name', 'company.region:id,state', 'leadStatus:id,name', 'customer:id,fullname,phone'])
                ->where('id', $id)
                ->first();
        if ($enquiry->lead_type == 'expo') {
            if (!empty($enquiry->expo_id)) {
                $enquiry->lead_type = $enquiry->expo->name;
                unset($enquiry->expo);
            }
        }
        return successResponse(trans('api.success'), $enquiry);
    }
}
