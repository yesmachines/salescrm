<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\RegionService;
use App\Services\CountryService;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, RegionService $regionService, CountryService $countryService)
    {
        //
        $input = $request->all();
        $regions = $regionService->getAllRegion($input);

        $countries = $countryService->getAllCountry();

        return view('regions.index', compact('regions', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //  return view('region.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RegionService $regionService)
    {
        $input = $request->all();

        $regionService->createRegion($input);

        return redirect()->route('regions.index')->with('success', 'Region created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, RegionService $regionService, CountryService $countryService)
    {
        //
        $countries = $countryService->getAllCountry();

        $region = $regionService->getRegion($id);

        $body = view('regions._edit')
            ->with(compact('region', 'countries'))
            ->render();

        return response()->json(array('success' => true, 'html' => $body));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, RegionService $regionService)
    {
        //
        $input = $request->all();

        $region = $regionService->getRegion($id);
        $regionService->updateRegion($region, $input);

        return redirect()->route('regions.index')->with('success', 'Region updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, RegionService $regionService)
    {
        //
        $regionService->DeleteRegion($id);

        return redirect()->route('regions.index')
            ->with('success', 'Region deleted successfully');
    }

    public function regionByCountry($id, RegionService $regionService)
    {
        $regions = $regionService->getAllRegion(['country_id' => $id]);


        return response()->json($regions);
    }
}
