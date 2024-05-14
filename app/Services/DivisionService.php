<?php

namespace App\Services;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionService
{
    public function getAllDivision($data = []): Object
    {
        return Division::where('status', 1)->orderBy('name', 'asc')->get();
    }
    public function getDivision($id): Object
    {
        return Division::find($id);
    }

    public function createDivision(array $userData): Division
    {
        return Division::create([
            'name'    => $userData['name'],
            'code'    => $userData['code']
        ]);
    }

    public function DeleteDivision($id): void
    {
        // delete user
        $division = Division::find($id);
        $division->delete();
    }

    public function updateDivision(Division $division, array $userData): void
    {
        $update = [];

        if (isset($userData['name']) && !empty($userData['name'])) {
            $update['name'] = $userData['name'];
        }
        if (isset($userData['code']) && !empty($userData['code'])) {
            $update['code'] = $userData['code'];
        }

        $division->update($update);
    }
    public function getDivisionList($data = []): Object
    {
        return Division::where('status', 1)->orderBy('name', 'asc')->get();
    }
}
