<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\EnquiryService;
use Carbon\Carbon;

class EnquiryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($visitorid, Request $request, EnquiryService $enquiryService)
    {
        //
        $input = $request->all();

        $input['visitorid'] = $visitorid;

        $enquiries = $enquiryService->getAllEnquiries($input);

        foreach ($enquiries as $k => $enquiry) {
            $enquiries[$k]->added_at = date('M d, Y', strtotime($enquiry->created_at));
        }

        return $this->sendResponse($enquiries, 'Enquiries listed successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, EnquiryService $enquiryService)
    {

        $data = $request->all();

        $enquiry = $enquiryService->createEnquiry($data);

        $enquiry->added_at = date('M d, Y', strtotime($enquiry->created_at));

        return $this->sendResponse($enquiry, 'Enquiry created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, EnquiryService $enquiryService)
    {
        $enquiry = $enquiryService->getEnquiry($id);

        if (is_null($enquiry)) {
            return $this->sendError('Enquiry not found.');
        }

        return $this->sendResponse($enquiry, 'Enquiry retrieved successfully.');
    }
}
