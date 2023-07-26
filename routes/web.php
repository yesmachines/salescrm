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
use App\Http\Controllers\RegionController;
use App\Http\Controllers\OrderController;
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
    Route::resource('regions', RegionController::class);
    Route::resource('leads', LeadController::class);
    Route::resource('orders', OrderController::class);
    Route::get('completedorders', [OrderController::class, 'completedOrders'])->name('orders.completed');

    // Route::get('visitors', [VisitorController::class, 'index'])->name('visitors.index');
    Route::get('customersbyid', [CustomerController::class, 'customersByCompany'])->name('customers.company');
    Route::get('companybyid', [CustomerController::class, 'companyById'])->name('customers.companyshow');

    Route::post('updateleadstatus', [LeadController::class, 'updateLeadStatus'])->name('leads.status');

    Route::get('quotations/download/{id}', [QuotationController::class, 'downloadQuotation'])->name('quotation.download');
    Route::resource('quotations', QuotationController::class);
    Route::post('quotations/reminder', [QuotationController::class, 'setReminder'])->name('quotation.setreminder');

    Route::get('convertlead/{id}', [QuotationController::class, 'convertToQuotation'])->name('leads.convert');
    Route::get('reviseQuotation/{id}', [QuotationController::class, 'reviseQuotation'])->name('quotation.revision');
    Route::post('updatequotationstatus', [QuotationController::class, 'updateQuotationStatus'])->name('quotation.status');
    Route::post('updatequotationstatuswithorder', [QuotationController::class, 'updateQuotationStatusWithOrder'])->name('quotationorder.status');
    Route::post('orderdetailsupdate', [OrderController::class, 'orderDetailsUpdate'])->name('order-details.update');
    Route::post('orderdeliverydetailsupdate', [OrderController::class, 'orderDeliveryDetailsUpdate'])->name('order-delivery-details.update');
    Route::post('orderhistorydetailsinsert', [OrderController::class, 'orderHistoryDetailsInsert'])->name('order-history-details.insert');
    Route::post('commentreplyinsert', [OrderController::class, 'commentReplyInsert'])->name('comment-reply.insert');
    Route::post('orderdatadeliveryhistoryload', [OrderController::class, 'orderDataDeliveryHistoryLoad'])->name('order-data-delivery-history.load');
    Route::post('vieworderdatadeliveryhistoryload', [OrderController::class, 'viewOrderDataDeliveryHistoryLoad'])->name('view-order-data-delivery-history.load');
    Route::post('orderhistorydelete', [OrderController::class, 'orderHistoryDelete'])->name('order-history.delete');
    Route::post('replycommentdelete', [OrderController::class, 'replyCommentDelete'])->name('reply-comment.delete');

    Route::post('orderdataitemhistorydelete', [OrderController::class, 'deleteOrderItem'])->name('order-data-item-history.delete');
    Route::post('orderdatadeliveryhistorybyidload', [OrderController::class, 'orderDataDeliveryHistoryByIdLoad'])->name('order-data-delivery-history-by-id.load');
    Route::post('vieworderdatadeliveryhistorybyidload', [OrderController::class, 'vieworderDataDeliveryHistoryByIdLoad'])->name('view-order-data-delivery-history-by-id.load');
    Route::post('updatedeliverybyidhistoryupdate', [OrderController::class, 'updateDeliveryByIdHistoryUpdate'])->name('update-delivery-by-id-history.update');
    Route::post('orderhistoryload', [OrderController::class, 'orderHistoryLoad'])->name('order-history.load');
    Route::get('leads/isqualified/{id}', [LeadController::class, 'checkToQualify'])->name('leads.qualify');
    Route::get('converted', [LeadController::class, 'convertedLeads'])->name('leads.converted');
    Route::get('winquotations', [QuotationController::class, 'winningQuotations'])->name('quotation.win');
    Route::get('allquotations', [QuotationController::class, 'allQuotations'])->name('quotation.listall');

    Route::get('reports/leads', [ReportController::class, 'leadsSummary'])->name('reports.leads');
    Route::get('reports/quotations', [ReportController::class, 'quotationSummary'])->name('reports.quotations');
    Route::get('reports/winstanding', [ReportController::class, 'employeeWinStanding'])->name('reports.winners');
    Route::get('reports/suppliers', [ReportController::class, 'brandSaleSummary'])->name('reports.suppliers');
    Route::get('reports/probability', [ReportController::class, 'probabilitySummary'])->name('reports.probability');
    Route::get('reports/employee', [ReportController::class, 'employeeLists'])->name('reports.employees');
    Route::get('reports/employee/{id}', [ReportController::class, 'employeeSalesProfile'])->name('reports.employeeprofile');

    Route::get('regionbycountry/{id}', [RegionController::class, 'regionByCountry'])->name('regions.country');
    Route::get('mycalender', [QuotationController::class, 'myReminderCalender'])->name('quotation.calender');

    Route::get('allcompany', [CustomerController::class, 'autosearchCompany'])->name('customers.loadcompanies');
    Route::post('allquotations', [QuotationController::class, 'updateWinDate'])->name('quotation.windate');


    // Route::get('dropbox/connect', function () {
    //     return Dropbox::connect();
    // });

    // Route::get('dropbox/disconnect', function () {
    //     return Dropbox::disconnect('app/dropbox');
    // });
});
