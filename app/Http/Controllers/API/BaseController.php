<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
    
    public function sendUserOtpEmail($user, $type, $email = null) {
        $userOtp = \App\Models\UserOtp::updateOrCreate(
                        ['user_id' => $user->id, 'type' => $type],
                        ['otp' => rand(111111, 999999), 'email' => $email]
        );

        $user->otp = $userOtp->otp;

        if ($type == 'fp_email') {
            $user->em_type = $userOtp->type;
            $user->sendEmailOtp();
        }
        return $userOtp->id;
    }
}
