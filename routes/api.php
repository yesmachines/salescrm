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

Route::group(['middleware' => ['api'], 'namespace' => 'App\Http\Controllers\API\User'], function () {
    Route::post('login', 'UserController@login');
    Route::post('forgot-password', 'PasswordResetController@forgotPassword');
    Route::post('resend-otp', 'PasswordResetController@resendOtp');
    Route::post('verify-otp', 'PasswordResetController@verifyOtp');
    Route::post('reset-password', 'PasswordResetController@resetPassword');
});

Route::group(['middleware' => ['auth:sanctum'], 'namespace' => 'App\Http\Controllers\API\User'], function () {
    Route::post('logout', 'UserController@logout');
    Route::get('profile', 'UserController@profile');
    Route::post('profile', 'UserController@updateProfile');
    Route::post('update-profile-image', 'UserController@updateProfileImage');
    Route::post('change-password', 'UserController@changePassword');
    Route::get('divisions', 'PresentationController@divisions');
    Route::get('brands/{division}', 'PresentationController@brands');
    Route::get('products/{division}/{brand_id?}', 'PresentationController@products');
    Route::get('product-details/{division}/{id}', 'PresentationController@productDetails');
    Route::get('product-references/{division}/{id}', 'PresentationController@references');
    Route::get('crm-brands/{module}', 'CrmController@brands');
    Route::get('crm-products/{module}/{brand_id?}', 'CrmController@products');
    /* Meting APIs */
    Route::post('meeting-create', 'MeetingController@store');
    Route::post('meeting-notes-create/{id}', 'MeetingController@notesStore');
    Route::get('meeting-list/{type}', 'MeetingController@list');
    Route::get('meeting-calender-list', 'MeetingController@calendarList');
    Route::post('meeting-feedback/{id}', 'MeetingController@feedback');
    Route::get('meeting-details/{id}', 'MeetingController@show');
    Route::delete('meeting-product/{id}', 'MeetingController@deleteProduct');
    Route::post('meeting-update/{id}', 'MeetingController@update');
    Route::post('meeting-notes', 'MeetingController@meetingNotes');
    /* Meting Shares APIs */
    Route::get('employees', 'MeetingShareController@employees');
    Route::post('share-meeting/{id}', 'MeetingShareController@share');
    Route::get('share-requests/{status}', 'MeetingShareController@requests');
    Route::post('confirm-share-request/{id}', 'MeetingShareController@confirmRequest');
    Route::post('shared-list', 'MeetingShareController@sharedList');
});

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
