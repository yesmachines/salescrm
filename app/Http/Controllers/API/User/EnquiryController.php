<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use App\Http\Resources\PaginateResource;
use Validator;
use App\Enums\EnquirySource;
use App\Enums\EnquiryMode;
use App\Enums\EnquiryPriority;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\LeadHistory;
use App\Models\Product;
use App\Models\LeadStatus;
use App\Models\LeadProduct;

class EnquiryController extends Controller {

    public function getExtras() {
        $data['modes'] = EnquiryMode::toKeyLabelArray();
        $data['statuses'] = LeadStatus::select('id', 'name')
                ->where('status', 1)
                ->orderBy('priority', 'asc')
                ->get();
        $data['priorities'] = EnquiryPriority::toKeyLabelArray();
        $data['coordinator'] = $this->getCoordinator();
        return successResponse(trans('api.success'), $data);
    }

    public function getStatus() {
        $data = LeadStatus::select('id', 'name')
                ->where('status', 1)
                ->orderBy('priority', 'asc')
                ->get();
        return successResponse(trans('api.success'), $data);
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
            'enquiry_mode' => 'required_if:type,internal',
            'expo_id' => 'required_if:type,expo',
            'enquiry_date' => 'required|date_format:Y-m-d',
            "company_id" => 'required',
            "customer_id" => 'required',
            'products' => 'required|array',
            'products.*.brand_id' => 'present|required_unless:products.*.product_id,null',
            'products.*.product_id' => 'present|required_unless:products.*.brand_id,null',
            'products.*.status_id' => 'required',
            'products.*.priority' => 'required',
            'products.*.notes' => 'present|required_if:products.*.product_id,null|nullable',
            'schedule_meeting' => 'required|boolean',
            'meeting_date' => 'required_if:schedule_meeting,1|date_format:Y-m-d',
            'meeting_time' => 'required_if:schedule_meeting,1|date_format:H:i',
            'timezone' => 'required_if:schedule_meeting,1|timezone',
            'location' => 'required_if:schedule_meeting,1'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $scheduled_notes = null;
        $product_count = 0;
        foreach ($request->products as $product) {
            $product_count++;
            $details = $product['notes'];
            $historyMessage = "New enquiry is created with status " . LeadStatus::find($product['status_id'])->name;
            if (!empty($product['brand_id']) && !empty($product['product_id'])) {
                $pdetails = Product::select(
                                \DB::raw("REPLACE(REPLACE(cm_products.title, '\\t', ''), '\\n', ' ') AS title"),
                                's.brand'
                        )
                        ->leftJoin('suppliers as s', 'products.brand_id', '=', 's.id')
                        ->where('products.id', '=', $product['product_id'])
                        ->first();
                //append to details and append shceduled notes
                $details .= ' | ' . $pdetails->title . ' | ' . $pdetails->brand;
                $scheduled_notes .= $details . ' | ';
            }
            //Save to leads with details
            $enquiry = new Lead();
            $enquiry->company_id = $request->company_id;
            $enquiry->customer_id = $request->customer_id;
            $enquiry->lead_type = $request->type;
            $enquiry->enquiry_date = $request->enquiry_date;
            $enquiry->details = $details;
            $enquiry->assigned_to = $request->user()->id;
            $enquiry->assigned_on = date('Y-m-d');
            $enquiry->status_id = $product['status_id'];
            if (!empty($request->enquiry_mode)) {
                $enquiry->enquiry_mode = $request->enquiry_mode;
            }
            if (!empty($request->expo_id)) {
                $enquiry->expo_id = $request->expo_id;
            }
            $enquiry->created_by = $request->user()->id;
            $enquiry->save();
            //Save to lead histories with historyMessage
            LeadHistory::create(
                    [
                        'lead_id' => $enquiry->id,
                        'status_id' => $enquiry->status_id,
                        'comment' => $historyMessage,
                        'priority' => $product['priority'],
                        'userid' => $enquiry->created_by,
                    ]
            );
            //Save to lead prodcuts
            LeadProduct::create(
                    [
                        'lead_id' => $enquiry->id,
                        'supplier_id' => $product['brand_id'],
                        'product_id' => $product['product_id'],
                        'notes' => $product['notes']
                    ]
            );
        }
        //if area id not null then send notification to manager using prodcut count;
        if (!empty($request->area_id)) {
            $enquiry->area_id = $request->area_id;
            $this->notifyEnquiryAreaMnager($enquiry, $product_count);
        }
        //if meeting schedule save with scheduled notes
        if ($request->schedule_meeting) {
            $this->createMeeting($request, $scheduled_notes);
        }
        return successResponse(trans('api.enquiry.created'));
    }

    public function createMeeting($request, $scheduled_notes) {
        $request->offsetUnset('area_id');
        $request['company_name'] = Company::select('company')
                        ->where('id', $request->company_id)
                        ->first()->company;
        $customer = Customer::where('id', $request->customer_id)->first();
        $request['company_representative'] = $customer->fullname;
        $request['phone'] = $customer->phone;
        $request['email'] = $customer->email;
        $request['title'] = "Meeting with " . $request->company_name;
        $request['scheduled_notes'] = $scheduled_notes;

        $meetingController = new \App\Http\Controllers\API\User\MeetingController();
        return $meetingController->store($request);
    }

    public function show(Request $request, $id) {
        $enquiry = Lead::select('id', 'company_id', 'customer_id', 'status_id', 'details', 'enquiry_date', 'lead_type', 'enquiry_mode', 'expo_id')
                ->with(['company:id,country_id,region_id,company', 'company.country:id,name', 'company.region:id,state', 'customer:id,fullname,phone'])
                ->where('id', $id)
                ->first();
        if ($enquiry->lead_type == 'expo') {
            if (!empty($enquiry->expo_id)) {
                $enquiry->lead_type = $enquiry->expo->name;
                unset($enquiry->expo);
            }
        }
        if (!empty($enquiry->enquiry_mode)) {
            $enquiry->enquiry_mode = $enquiry->enquiry_mode_label;
        }

        $enquiry->product = LeadProduct::selectRaw("REPLACE(REPLACE(cm_p.title, '\\t', ''), '\\n', ' ') AS title,cm_s.brand,cm_lead_products.notes")
                ->leftJoin('suppliers as s', 'lead_products.supplier_id', '=', 's.id')
                ->leftJoin('products as p', 'lead_products.product_id', '=', 'p.id')
                ->where('lead_products.lead_id', '=', $enquiry->id)
                ->first();

        //Get latest history;
        $lleadHistory = LeadHistory::where('lead_id', $enquiry->id)->orderBy('created_at', 'desc')->first();
        if ($lleadHistory) {
            $enquiry->priority = $lleadHistory->priority;
        } else {
            $enquiry->priority = 'low';
        }

        $requestedTimezone = $request->header('timezone', config('app.timezone'));

        $enquiry->calls->each(function ($call) use ($requestedTimezone) {
            $meetingTimeInUserTimezone = Carbon::parse($call->called_at, 'UTC')->setTimezone($requestedTimezone);
            $call->date = $meetingTimeInUserTimezone->format('Y-m-d');
            $call->time = $meetingTimeInUserTimezone->format('h:i A');
        });

        return successResponse(trans('api.success'), $enquiry);
    }

    public function update(Request $request, $id) {
        $enquiryPs = array_column(EnquiryPriority::cases(), 'value');
        $rules = [
            'status_id' => 'required',
            'priority' => ['required', 'in:' . implode(',', $enquiryPs)],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        //set Comment
        $historyMessage = "Enquiry is updated with status " . LeadStatus::find($request->status_id)->name;
        LeadHistory::firstOrCreate(
                ['lead_id' => $id, 'status_id' => $request->status_id, 'priority' => $request->priority],
                ['comment' => $historyMessage, 'userid' => $request->user()->id]
        );
        return successResponse(trans('api.success'));
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
