<?php

function cdn($asset) {
    //Check if we added cdn's to the config file
    if (!env('CDN_ENABLED', false)) {
        return Storage::disk('public')->url($asset);
    } else {
        return Storage::disk('s3')->url(env('CDN_FILE_DIR', 'dev/test/') . $asset);
    }
}

function successResponse($status, $data = null) {
    if (!empty($data)) {
        return response()->json([
                    "status" => 200,
                    "message" => $status,
                    "data" => $data,
                        ], 200);
    } else {
        return response()->json([
                    "status" => 10000,
                    "message" => $status
                        ], 200);
    }
}

function errorResponse($status, $errorMessage = null, $eCode = 400) {
    return response()->json([
                "status" => 400,
                "message" => $status,
                "errorMessage" => $errorMessage
                    ], $eCode);
}

function systemResponse($status) {
    return response()->json([
                "statusCode" => 500,
                "message" => $status
                    ], 500);
    //return response()->json(['error' => $status, 401]);
}

function tokenResponse($status) {
    return response()->json([
                "status" => 401,
                "message" => $status
                    ], 401);
}
