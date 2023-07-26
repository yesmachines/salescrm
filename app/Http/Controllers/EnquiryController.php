<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EnquiryService;

class EnquiryController extends Controller
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

        $enquiries = $enquiryService->allEnquiries($input);

        return view('enquiries.index', compact('enquiries'));
    }
}
