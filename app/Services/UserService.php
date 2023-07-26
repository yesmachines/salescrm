<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UserService
{

    public function createUser(array $userData): User
    {
        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password'])
        ]);
    }

    public function assignUserRoles($userData, User $user): void
    {
        $roles = $userData['roles'] ?? [];
        $user->assignRole($roles);
    }

    public function updateUser(array $userData): void
    {
        $update = [
            'name' => $userData['name'],
            'email' => $userData['email']
        ];
        if (!empty($userData['password'])) {
            $update['password'] = Hash::make($userData['password']);
        }

        $user = User::find($userData['user_id']);

        $user->update($update);

        $user->syncRoles($userData['roles']);
    }

    public function roleByCoordinators(array $userids): Object
    {
        return User::whereIn('id', $userids)->whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'coordinators');
            }
        )->get();
    }

    public function getUser($userid): Object
    {
        return User::find($userid);
    }
}
