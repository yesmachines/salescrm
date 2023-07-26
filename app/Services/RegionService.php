<?php

namespace App\Services;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionService
{
    public function getAllRegion($data = []): Object
    {
        $sql =  Region::where('status', 1);
        if (isset($data['country_id']) && $data['country_id']) {
            $sql->where('country_id', $data['country_id']);
        }
        $region = $sql->orderBy('state', 'asc')->get();

        return $region;
    }
    public function getRegion($id): Object
    {
        return Region::find($id);
    }

    public function createRegion(array $userData): Region
    {
        return Region::create([
            'state'         => $userData['state'],
            'country_id'    => $userData['country_id']
        ]);
    }

    public function DeleteRegion($id): void
    {
        // delete user
        $region = Region::find($id);
        $region->delete();
    }

    public function updateRegion(Region $region, array $userData): void
    {
        $update = [];

        if (isset($userData['state']) && !empty($userData['state'])) {
            $update['state'] = $userData['state'];
        }
        if (isset($userData['country_id']) && !empty($userData['country_id'])) {
            $update['country_id'] = $userData['country_id'];
        }

        $region->update($update);
    }
}
