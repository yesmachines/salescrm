<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Database\QueryException;

class PasswordResetController extends BaseController {

    public function __construct() {
        //$this->middleware('guest:api');
    }

    public function forgotPassword(Request $request) {
        $rules = [
            'otp_type' => 'required|in:email,sms',
            'email' => 'required_if:otp_type,email',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        switch ($request->otp_type) {
            case 'email':
                $user = User::where('email', $request->email)
                        ->first();
                break;
        }

        if ($user) {
            if ($request->otp_type == 'email') {
                $ref_id = $this->sendUserOtpEmail($user, 'fp_email');
            }
            return successResponse(trans('api.success'), ['ref_id' => $ref_id]);
        }
        return errorResponse(trans('api.user_not_exist'));
    }

    public function resendOtp(Request $request) {
        $rules = [
            'ref_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $userOtp = UserOtp::where('id', $request->ref_id)
                ->first();
        if ($userOtp) {
            $user = User::find($userOtp->user_id);
            if (!empty($user)) {
                //$user->email = "shainu.giraf@gmail.com";
                $ref_id = $this->sendUserOtpEmail($user, $userOtp->type, $userOtp->email);
                return successResponse(trans('api.success'), ['ref_id' => $ref_id]);
            }
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function verifyOtp(Request $request) {
        $rules = [
            'ref_id' => 'required',
            'otp' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $userOtp = UserOtp::where('id', $request->ref_id)
                ->where('otp', $request->otp)
                ->first();

        if ($userOtp) {
            if (($userOtp->type == 'fp_email')) {
                $userOtp->verified_at = date('Y-m-d H:i:s');
                $userOtp->save();
                return successResponse(trans('api.success'), ['ref_id' => $userOtp->id]);
            }
        }
        return errorResponse(trans('api.invalid_otp'));
    }

    public function resetPassword(Request $request) {
        $rules = [
            'ref_id' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $userOtp = UserOtp::where('id', $request->ref_id)
                ->whereNotNull('otp')
                ->whereNotNull('verified_at')
                ->first();

        if ($userOtp) {
            $pendingTime = new \DateTime();
            $pendingTime->modify('-60 min');
            $pendingDate = $pendingTime->format('Y-m-d H:i:s');
            if ($userOtp->verified_at > $pendingDate) {
                $user = User::find($userOtp->user_id);
                if ($user) {
                    $user->password = bcrypt($request->password);
                    //Unset active token if any
                    try {
                        $user->tokens()->delete();
                    } catch (\Exception $e) {
                        
                    }
                    $user->save();
                    $userOtp->delete();
                    return successResponse(trans('api.password.changed'));
                }
            }
        }
        return errorResponse(trans('api.otp_expired'));
    }
}
