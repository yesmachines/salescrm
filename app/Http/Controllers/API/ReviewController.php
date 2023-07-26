<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Services\ReviewService;

class ReviewController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ReviewService $reviewService)
    {
        //
        $input = $request->all();

        $reviews = $reviewService->feedbacksByCustomer($input);

        return $this->sendResponse($reviews, 'Reviews listed successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, ReviewService $reviewService)
    {

        $data = $request->all();

        $review = $reviewService->createFeedback($data);

        return $this->sendResponse($review, 'Reviews created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, ReviewService $reviewService)
    {
        $review = $reviewService->getFeedback($id);

        if (is_null($review)) {
            return $this->sendError('Review not found.');
        }

        return $this->sendResponse($review, 'Review retrieved successfully.');
    }
}
