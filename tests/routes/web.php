<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LoginController::class, 'showLoginForm']);
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return "All Cache is cleared";
    // return view('cache');
})->name('cache.clear');

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return "Image link makes public";
})->name('storage.link');

Route::group(['middleware' => ['auth', 'permission']], function () {
    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionsController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('visitors', VisitorController::class);
    Route::resource('enquiries', EnquiryController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('categories', CategoryController::class);
    // Route::get('visitors', [VisitorController::class, 'index'])->name('visitors.index');

    Route::get('customersbyid', [CustomerController::class, 'customersByCompany'])->name('customers.company');

    Route::resource('leads', LeadController::class);
    Route::post('updateleadstatus', [LeadController::class, 'updateLeadStatus'])->name('leads.status');

    Route::resource('quotations', QuotationController::class);
    Route::get('convertlead/{id}', [QuotationController::class, 'convertToQuotation'])->name('leads.convert');
    Route::get('reviseQuotation/{id}', [QuotationController::class, 'reviseQuotation'])->name('quotation.revision');
    Route::post('updatequotationstatus', [QuotationController::class, 'updateQuotationStatus'])->name('quotation.status');

    Route::get('reports/bullshitleads', [ReportController::class, 'bullshitLeads'])->name('reports.bullshit');
    Route::get('reports/futureleads', [ReportController::class, 'futureLeads'])->name('reports.future');
    Route::get('reports/openleads', [ReportController::class, 'openLeads'])->name('reports.open');
    Route::get('reports/potentialleads', [ReportController::class, 'potentialLeads'])->name('reports.potential');
    Route::get('reports/highwinning', [ReportController::class, 'highWinningPercent'])->name('reports.winning');

    // Route::get('dropbox/connect', function () {
    //     return Dropbox::connect();
    // });

    // Route::get('dropbox/disconnect', function () {
    //     return Dropbox::disconnect('app/dropbox');
    // });
});
