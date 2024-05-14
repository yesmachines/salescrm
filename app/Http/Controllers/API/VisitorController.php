<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\VisitorService;

class VisitorController extends BaseController
{


    public function store(Request $request, VisitorService $guestService)
    {
        $data = $request->all();
        $visitor = $guestService->createGuest($data);

        $input = [
            'visitor_id'    => $visitor->id,
            'purpose'       => "Open House Registration 2024"
        ];
        $guestService->createVisitorLog($input);


        return $this->sendResponse($visitor, 'Visitor registered successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, VisitorService $guestService)
    {
        $visitor = $guestService->getVisitor($id);

        if (is_null($visitor)) {
            return $this->sendError('Visitor not found.');
        }


        return $this->sendResponse($visitor, 'Visitor retrieved successfully.');
    }


    public function getVisitors(Request $request, VisitorService $guestService)
    {
        $input = $request->all();
        $data = $guestService->getAllVisitor($input);

        return response()->json($data);
    }
}
