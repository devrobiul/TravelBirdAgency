<?php

use App\Http\Controllers\Admin\AgencyAccountController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\GroupTicketController;
use App\Http\Controllers\Admin\HotelbookingController;
use App\Http\Controllers\Admin\IncomeCategoryController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\PassportController;
use App\Http\Controllers\Admin\PdfDownloadController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SingleTicketController;
use App\Http\Controllers\Admin\RefundTicketController;
use App\Http\Controllers\Admin\ManpowerController;
use App\Http\Controllers\Admin\OfficeNoteController;
use App\Http\Controllers\Admin\OtherServiceController;
use App\Http\Controllers\Admin\VisaSaleController;
use App\Models\IncomeCategory;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/change/password', 'changePassword')->name('changePassword');
    Route::post('/change/password', 'passwordUpdate')->name('passwordUpdate');
    Route::get('/cache/clear', 'cacheClear')->name('cacheClear');
    Route::post('/logout', 'logout')->name('logout');
});

// Customer Route
Route::controller(CustomerController::class)->prefix('people')->name('customer.')->group(function () {
    Route::get('customer/{type}', 'index')->name('index');
    Route::get('customer/show/{id}', 'show')->name('show');
    Route::get('customer/create/user', 'create')->name('create');
    Route::post('customer/store', 'store')->name('store');
    Route::get('customer/edit/{id}', 'edit')->name('edit');
    Route::put('customer/update/{id}', 'update')->name('update');
    Route::delete('customer/destroy/{id}', 'destroy')->name('destroy');
    Route::get('customer/details/{slug}', 'details')->name('details');
    Route::post('customer/transaction/{id}', 'transaction')->name('transaction');
    Route::get('customer/all/transaction/{slug}', 'allTransaction')->name('allTransaction');
    Route::delete('customer/transaction/delete/{id}', 'transactionDelete')->name('transactionDelete');
    Route::get('customer/sale/report/{id}', 'customerSaleReport')->name('saleReport');
    Route::get('customer/due/list', 'dueCustomerList')->name('dueCustomerList');
    Route::get('customer/sale/purchase/data/{id}', 'salePurchaseData')->name('salePurchaseData');
    Route::post('customer/bulk-status-update','bulkStatusUpdate')->name('bulk-status-update');
    Route::get('customer/bulk/pdf', 'bulkPdf')->name('bulk.pdf');

});

// Refund ticket
Route::controller(RefundTicketController::class)->name('inventory.refundticket.')->group(function () {
    Route::get('/refund/ticket', 'index')->name('index');
    Route::get('/refund/ticket/{id}', 'edit')->name('edit');
    Route::put('/refund/ticket/{id}', 'update')->name('update');
    Route::get('/refund/ticket/status/{id}', 'status')->name('status');
    Route::post('/refund/ticket/status/{id}', 'statusStore')->name('statusStore');
    Route::post('/refund/ticket/store', 'store')->name('refundStore');
    Route::delete('/refund/ticket/delete/{id}', 'destroy')->name('destroy');
    Route::get('/refund/search/ticket', 'ticketSearch')->name('ticketSearch');
});

// DPFgenerate route
Route::controller(PdfDownloadController::class)->group(function () {
    Route::get('/single/ticket/generate/pdf/{id}', 'singleTicketPdf')->name('singleTicketPdf');
    Route::get('/hotel/booking/generate/pdf/{id}', 'hotelbookingPdf')->name('hotelbookingPdf');
    Route::get('/passport/sale/generate/pdf/{id}', 'passportSalePdf')->name('passportSalePdf');
    Route::get('/manpower/sale/generate/pdf/{id}', 'manpowerSalePdf')->name('manpowerSalePdf');
    Route::get('/visa/sale/generate/pdf/{id}',     'visaSalePdf')->name('visaSalePdf');
    Route::get('/group/ticket/generate/pdf/{id}', 'grouptikceSalePdf')->name('grouptikceSalePdf');
    Route::post('/b2bpay/customer/generate/pdf/{id}', 'b2bTransactionReport')->name('b2bTransactionReport');
    Route::post('/customer/balance/sheet/generate/pdf/{id}', 'customerBalanceSheetPdf')->name('customerBalanceSheetPdf');
    Route::get('/customer/bill/generate/pdf/{id}', 'billGeneratePDf')->name('billGeneratePDf');
    
});

// Transaction Route
Route::controller(TransactionController::class)->prefix('transaction')->name('accounts.transaction.')->group(function () {
    Route::get('/{type}/index', 'index')->name('index');
    Route::get('/show/{type}/{id}', 'show')->name('show');
    Route::get('/create/{type}', 'create')->name('create');
    Route::post('/store/{type}', 'store')->name('store');
    Route::get('/edit/{type}/{id}', 'edit')->name('edit');
    Route::put('/update/{type}/{id}', 'update')->name('update');
    Route::delete('/destroy/{type}/{id}', 'destroy')->name('destroy');
});

// Report Route
Route::controller(ReportController::class)->group(function () {
    Route::get('/report/transaction', 'transaction')->name('report.transaction');
    Route::post('/report/transaction', 'transactionReport')->name('report.transactionReport');
    Route::get('/report/expense/', 'expenseReport')->name('report.expenseReport');
    Route::post('/report/expense/pdf', 'expenseReportPdf')->name('report.expenseReportPdf');
    Route::get('/report/sale/purchase', 'saleReport')->name('report.saleReport');
    Route::post('/report/sale/purchase/pdf', 'saleReportPdf')->name('report.saleReportPdf');
    Route::get('/report/profit/loss/account', 'profitloss')->name('report.profitloss');
    Route::get('/report/profit/loss/search', 'profitlossSearch')->name('report.profitlossSearch');
});


// User Route
Route::controller(UserController::class)->prefix('system')->name('users.')->group(function () {
    Route::get('/user/index', 'index')->name('index');
    Route::get('/user/show/{id}', 'show')->name('show');
    Route::post('/user/store', 'store')->name('store');
    Route::get('/user/edit/{id}', 'edit')->name('edit');
    Route::put('/user/update/{id}', 'update')->name('update');
    Route::delete('/user/destroy/{id}', 'destroy')->name('destroy');
    Route::post('/user/status/update/{id}', 'statusUpdate')->name('status.update');
    Route::get('/user/login/{id}', 'logUsingId')->name('logUsingId');
});

// Setting Route
Route::controller(SettingController::class)->group(function () {
    Route::get('/general/index', 'general')->name('setting.general');
    Route::get('/information/index', 'information')->name('setting.information');
    Route::post('/setting/store', 'store')->name('setting.store');
});


// Resourse Route
Route::resource('product', ProductController::class)->names('product');
Route::resource('single/ticket', SingleTicketController::class)->names('inventory.singleticket');
Route::resource('hotel/booking', HotelbookingController::class)->names('inventory.hotel');
Route::resource('passport', PassportController::class)->names('inventory.passport');
Route::resource('agency/account', AgencyAccountController::class)->names('accounts');
Route::resource('manpower', ManpowerController::class)->names('inventory.manpower');
Route::resource('visa', VisaSaleController::class)->names('inventory.visasale');
Route::resource('group/ticket', GroupTicketController::class)->names('inventory.groupticket');
Route::resource('expense', ExpenseController::class)->names('expense');
Route::resource('note', OfficeNoteController::class)->names('note');
Route::resource('other/service', OtherServiceController::class)->names('inventory.other');
Route::resource('expense/category/index', CategoryController::class)->names('expense.category');
Route::resource('extra/income/category', IncomeCategoryController::class)->names('income.category');
Route::resource('extra/income', IncomeController::class)->names('income');