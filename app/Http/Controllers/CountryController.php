<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\CountryService;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CountryService $countryService)
    {
        //
        $input = $request->all();
        $countries = $countryService->getAllCountry($input);

        return view('country.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //  return view('country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CountryService $countryService)
    {
        $input = $request->all();

        $countryService->createCountry($input);

        return redirect()->route('countries.index')->with('success', 'Country created successfully');
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
    public function edit($id, CountryService $countryService)
    {
        //
        $country = $countryService->getCountry($id);

        $body = view('country._edit')
            ->with(compact('country'))
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
    public function update(Request $request, $id, CountryService $countryService)
    {
        //
        $input = $request->all();

        $country = $countryService->getCountry($id);
        $countryService->updateCountry($country, $input);

        return redirect()->route('countries.index')->with('success', 'Country updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, CountryService $countryService)
    {
        //
        $countryService->DeleteCountry($id);

        return redirect()->route('countries.index')
            ->with('success', 'Country deleted successfully');
    }
}
