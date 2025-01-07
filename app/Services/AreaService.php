<?php

namespace App\Services;

use Illuminate\Http\Request;
use DB;
use App\Models\Area;
use App\Models\EmployeeArea;

class AreaService {

    public function getAreas(): Object {
        return Area::where('status', 1)->get();
    }

    public function createArea(array $userData): void {
        if (isset($userData['name']) && !empty($userData['name'])) {
            Area::create($userData);
        }
    }

    public function updateArea(Area $area, array $userData): void {
        if (isset($userData['name']) && !empty($userData['name'])) {
            $area->update($userData);
        }
    }

    public function addEmployeeAreas(array $userData, $user): void {
        if (isset($userData['area_id']) && !empty($userData['area_id'])) {
            $user->areas()->attach($userData['area_id']);
        }
    }

    public function updateEmployeeAreas(array $userData, $user) {
        if (isset($userData['area_id']) && !empty($userData['area_id'])) {
            $user->areas()->sync($userData['area_id']);
        }
    }
}
