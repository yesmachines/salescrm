<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentService
{
    public function getAllDepartment($data = []): Object
    {
        return Department::where('status', 1)->orderBy('name', 'asc')->get();
    }
    public function getDepartment($id): Object
    {
        return Department::find($id);
    }

    public function createDepartment(array $userData): Department
    {
        return Department::create([
            'name'    => $userData['name'],
            'code'    => $userData['code']
        ]);
    }

    public function DeleteDepartment($id): void
    {
        // delete user
        $department = Department::find($id);
        $department->delete();
    }

    public function updateDepartment(Department $department, array $userData): void
    {
        $update = [];

        if (isset($userData['name']) && !empty($userData['name'])) {
            $update['name'] = $userData['name'];
        }
        if (isset($userData['code']) && !empty($userData['code'])) {
            $update['code'] = $userData['code'];
        }

        $department->update($update);
    }
}
