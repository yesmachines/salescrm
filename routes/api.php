<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\VisitorController;
use App\Http\Controllers\API\EnquiryController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['cors'])->group(function () {
    Route::post('visitors', [VisitorController::class, 'store']);
    Route::get('visitors/{id}', [VisitorController::class, 'show']);
    Route::get('enquiries/{visitorid}', [EnquiryController::class, 'index']);
    Route::post('enquiries', [EnquiryController::class, 'store']);
    Route::get('enquiry/{id}', [EnquiryController::class, 'show']);

    Route::get('/livesearch', [VisitorController::class, 'getVisitors']);


    Route::post('shortlink', [OrderController::class, 'shortLink']);
    Route::post('ponumber', [OrderController::class, 'searchByPoNumber']);
    Route::post('validatenumber', [OrderController::class, 'validateNumber']);
    Route::post('validatepo', [OrderController::class, 'validatePoNumber']);
    Route::post('validateotp', [OrderController::class, 'validateOtp']);

    Route::post('sendotpviaemail', [OrderController::class, 'sendOtpViaEmail']);
    Route::post('addcomment', [OrderController::class, 'addComment']);

    Route::get('comments', [OrderController::class, 'getComments']);
});


// Route::post('fetch-order-items-by-delivery-id', [OrderController::class, 'fetchOrderItemsBydeliveryID']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
