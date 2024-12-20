<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\EmployeeService;

class UserController extends Controller {

    public function login(Request $request) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return errorResponse(trans('api.invalid_credentials'));
        }

        if ($user->hasAnyRole('salesmanager', 'satellite')) {
            //To delete all token means to logout form other logged in devices
            //$user->tokens()->delete();
            $user->access_token = $user->createToken('auth_token')->plainTextToken;
            return successResponse(trans('api.success'), $this->userData($user));
        }
        return errorResponse(trans('api.invalid_credentials'));
    }

    public function profile(Request $request) {
        $user = $request->user();
        return successResponse(trans('api.success'), $this->userData($user));
    }

    public function updateProfile(Request $request) {
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'designation' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        $user->employee()->update($request->except(['name']));

        return successResponse(trans('api.success'));
    }

    public function updateProfileImage(Request $request, EmployeeService $employeeService) {
        $rules = [
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $user = $request->user();
        ($user->employee->image_url) ? $employeeService->deleteImage($user->employee->image_url) : '';
        $avatar = $employeeService->uploadAvatar($request);
        $user->employee()->update(['image_url' => $avatar]);

        return successResponse(trans('api.success'), ['image_url' => asset('storage/' . $avatar)]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return successResponse(trans('api.success'));
    }

    public function userData(&$user) {
        $user->with('employee');
        $user->employee->image_url = !empty($user->employee->image_url) ? asset('storage/' . $user->employee->image_url) : null;
        return $user;
    }
}
