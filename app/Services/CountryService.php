<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryService
{
    public function getAllCountry($data = []): Object
    {
        return Country::where('status', 1)->orderBy('name', 'asc')->get();
    }
    public function getCountry($id): Object
    {
        return Country::find($id);
    }

    public function createCountry(array $userData): Country
    {
        return Country::create([
            'name'    => $userData['name'],
            'code'    => $userData['code']
        ]);
    }

    public function DeleteCountry($id): void
    {
        // delete user
        $country = Country::find($id);
        $country->delete();
    }

    public function updateCountry(Country $country, array $userData): void
    {
        $update = [];

        if (isset($userData['name']) && !empty($userData['name'])) {
            $update['name'] = $userData['name'];
        }
        if (isset($userData['code']) && !empty($userData['code'])) {
            $update['code'] = $userData['code'];
        }

        $country->update($update);
    }
}
