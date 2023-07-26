<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\EnquiryService;

class EnquiryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EnquiryService $enquiryService)
    {
        //
        $input = $request->all();

        $enquiries = $enquiryService->getAllEnquiries($input);

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
