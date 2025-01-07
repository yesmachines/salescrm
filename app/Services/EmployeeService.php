<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use App\Models\Target;
use Illuminate\Http\Request;
use Image;
use DB;
use Illuminate\Support\Facades\File;

class EmployeeService
{
    public function uploadAvatar(Request $request): ?string
    {
        $imageUrl = "";
        if ($request->hasfile('image_url')) {

            $file = $request->file('image_url');
            $assetName = $request->input('name') . time();
            // generate a new filename. getClientOriginalExtension() for the file extension
            $filename =  $assetName . '.' . $file->getClientOriginalExtension();
            $imageUrl = 'employees/' . $filename;

            // save to storage/app/public/employees as the new $filename
            $image = Image::make($file);
            $image->save(storage_path('app/public/' . $imageUrl));
        }
        return $imageUrl;
    }

    public function createEmployee(array $userData, User $user, string $imageUrl): Employee
    {
        $employee =  Employee::create([
            'user_id'     => $user->id,
            'emp_num'     => $userData['emp_num'],
            'phone'       => $userData['phone'],
            'designation' => $userData['designation'],
            'division'    => $userData['division'],
            'image_url'   => $imageUrl
        ]);

        if (isset($userData['target_value']) && isset($userData['fiscal_year'])) {
            Target::create([
                'employee_id'   => $employee->id,
                'target_value'  => $userData['target_value'],
                'fiscal_year'   => $userData['fiscal_year']
            ]);
        }

        return $employee;
    }

    public function getEmployee($id): Employee
    {
        return Employee::find($id);
    }

    public function getEmployeeTarget($id, $data = []): Object
    {
        $sql =  Target::where('employee_id', $id);
        if (isset($data['fiscal_year']) && $data['fiscal_year']) {
            $sql->where('fiscal_year', $data['fiscal_year']);
        }
        $target = $sql->orderBy('id', 'desc')
            ->first();

        return $target;
    }

    public function getEmployeeByUserId($id): Employee
    {
        return Employee::where('user_id', $id)->first();
    }

    public function getAllEmployee($data = []): Object
    {
        $authorizedRoles = ['divisionmanager', 'salesmanager', 'coordinators','admin', 'satellite'];

        $sql = Employee::with('user')
            ->with('user.roles')->whereHas('user.roles', function ($query) use ($authorizedRoles) {
                $query->whereIn('name', $authorizedRoles);
            });

        // if (isset($data['status']) && !empty($data['status'])) {
        //     $sql->where('status', '=', $data['status']);
        // }
        $sql->where('status', '=', 1);

        $employees = $sql->get()->sortBy('user.name');

        return $employees;
    }


    public function listEmployees($currentid = 0): Object
    {
        $sql = Employee::orderBy('id', 'desc');
        if (!$currentid) {
            $employees = $sql->get();
        } else {
            $employees = $sql->where('user_id', '!=', $currentid)->get();
        }
        return $employees;
    }

    public function deleteImage(string $image_url): void
    {
        // delete image
        if ($image_url) {
            $image_path = storage_path('app/public/') . $image_url; // upload path
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }
    }

    public function DeleteEmployee(Employee $emp): void
    {
        // delete user
        User::find($emp->user_id)->delete();

        Target::where('employee_id', $emp->id)->delete();

        $emp->delete(); // Employee Delete
    }

    public function updateEmployee(Employee $employee, array $userData, string $imageUrl = null): void
    {
        $update = [
            'emp_num'     => $userData['emp_num'],
            'phone'       => $userData['phone'],
            'designation' => $userData['designation'],
            'division'    => $userData['division']
        ];
        if (!empty($imageUrl)) {
            $update['image_url'] = $imageUrl;
        }

        $employee->update($update);

        if (isset($userData['target_value']) && isset($userData['fiscal_year'])) {

            $target = Target::where([
                'fiscal_year'  => $userData['fiscal_year'],
                'employee_id'  => $employee->id
            ])->first();

            if ($target) {
                $target->update([
                    'target_value'  => $userData['target_value'],
                    'fiscal_year'   => $userData['fiscal_year']
                ]);
            } else {
                // new
                Target::create([
                    'employee_id'   => $employee->id,
                    'target_value'  => $userData['target_value'],
                    'fiscal_year'   => $userData['fiscal_year']
                ]);
            }
        }
    }
}
