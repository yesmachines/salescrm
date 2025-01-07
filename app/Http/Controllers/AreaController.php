<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AreaService;

class AreaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, AreaService $areaService) {
        $areas = $areaService->getAreas();
        $timezones = timezone_identifiers_list();
        $timezones = array_combine($timezones, $timezones);
        return view('areas.index', compact('areas', 'timezones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AreaService $areaService) {
        $input = $request->all();
        $areaService->createArea($input);
        return redirect()->route('areas.index')->with('success', 'Area created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $area = \App\Models\Area::FindOrFail($id);
        $timezones = timezone_identifiers_list();
        $timezones = array_combine($timezones, $timezones);
        return view('areas.edit', compact('area', 'timezones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, AreaService $areaService) {
        $input = $request->all();
        $area = \App\Models\Area::FindOrFail($id);
        $areaService->updateArea($area, $input);

        return redirect()->route('areas.index')->with('success', 'Area updated successfully');
    }
}
