<?php

namespace App\Services;


use App\Models\Employee;
use Illuminate\Http\Request;
use DB;
use App\Models\User;

class EmployeeManagerService
{
    public function addManagers(array $userData, $empId): void
    {
        if (isset($userData['manager_id']) && !empty($userData['manager_id'])) {

            foreach ($userData['manager_id'] as $managerid) {
                DB::table('employee_managers')->insert([
                    'employee_id' => $empId,
                    'manager_id'  => $managerid
                ]);
            }
        }
    }

    public function getManagers($empId): array
    {
        return DB::table('employee_managers')
            ->where('employee_id', $empId)
            ->pluck('manager_id')
            ->toArray();
    }

    public function getCoordinators($empId, $userService): Object
    {
        $childIds =  DB::table('employee_managers as em')
            ->join('employees as e', 'e.id', '=', 'em.employee_id')
            ->where('em.manager_id', $empId)
            ->pluck('e.user_id')
            ->toArray();

        $users = $userService->roleByCoordinators($childIds);

        return $users;
    }

    public function updateManager(array $userData, $empId)
    {
        if (isset($userData['manager_id']) && !empty($userData['manager_id'])) {

            $existingManagers =  $this->getManagers($empId);
            $newArr = array_diff($existingManagers, $userData['manager_id']);

            foreach ($userData['manager_id'] as $managerid) {

                if (in_array($managerid, $existingManagers)) {

                    // unset($newArr['']);
                    continue;
                } else {
                    DB::table('employee_managers')->insert([
                        'employee_id' => $empId,
                        'manager_id'  => $managerid
                    ]);
                }
            }
            // delete unselected ids
            DB::table('employee_managers')
                ->where('employee_id', $empId)
                ->whereIn('manager_id', $newArr)
                ->delete();
        } else {
            DB::table('employee_managers')
                ->where('employee_id', $empId)
                ->delete();
        }
    }
}
