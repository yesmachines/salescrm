<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
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

    public function store(Request $request) {
        $enquiryTypes = array_column(EnquirySource::cases(), 'value');
        $rules = [
            'type' => ['required', 'in:' . implode(',', $enquiryTypes)],
            'expo_id' => 'required_if:type,expo',
            "company_id" => 'required',
            "customer_id" => 'required',
            "status_id" => 'required',
            "details" => 'required',
            'enquiry_date' => 'required|date_format:Y-m-d',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $enquiry = new Lead();
        $enquiry->company_id = $request->company_id;
        $enquiry->customer_id = $request->customer_id;
        $enquiry->lead_type = $request->type;
        $enquiry->enquiry_date = $request->enquiry_date;
        $enquiry->details = $request->details;
        $enquiry->assigned_to = $request->user()->id;
        $enquiry->assigned_on = date('Y-m-d');
        $enquiry->status_id = $request->status_id;
        if (!empty($request->expo_id)) {
            $enquiry->expo_id = $request->expo_id;
        }
        $enquiry->created_by = $request->user()->id;
        $enquiry->save();
        return successResponse(trans('api.meeting.created'), ['enquiry_id' => $enquiry->id]);
    }

    public function show(Request $request, $id) {
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

        $requestedTimezone = $request->header('timezone', config('app.timezone'));

        $enquiry->calls->each(function ($call) use ($requestedTimezone) {
            $meetingTimeInUserTimezone = Carbon::parse($call->called_at, 'UTC')->setTimezone($requestedTimezone);
            $call->date = $meetingTimeInUserTimezone->format('Y-m-d');
            $call->time = $meetingTimeInUserTimezone->format('h:i A');
        });

        return successResponse(trans('api.success'), $enquiry);
    }

    public function callLogs(Request $request) {
        $rules = [
            'enquiry_id' => 'required',
            'call_status' => 'required|in:declined,no-answer,call-busy,connected,unavailable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        try {
            $enquiry = Lead::findOrFail($request->enquiry_id);
            $requestedTimezone = $request->header('timezone', config('app.timezone'));
            $currentTimeInUserTimezone = Carbon::now($requestedTimezone);
            $currentTimeInUTC = $currentTimeInUserTimezone->copy()
                    ->setTimezone('UTC')
                    ->toDateTimeString();
            $request['called_at'] = $currentTimeInUTC;
            $request['timezone'] = $requestedTimezone;
            $enquiry->calls()->create($request->all());
            return successResponse(trans('api.success'));
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }
}
