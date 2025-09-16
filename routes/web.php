<?php

use App\Http\Controllers\Frotnend\ClientSideController;
use Illuminate\Support\Facades\Route;


Route::controller(ClientSideController::class)->group(function () {
    Route::get('/client/sheet/{slug}/{uuid}', 'customerCheckReport')->name('customerCheckReport');
    Route::get('/client/sheet/view/{id}/{uuid}', 'customerCheckReportview')->name('customerCheckReportview');
    Route::get('/client/sheet/download/{id}/{uuid}', 'customerCheckReportdwonload')->name('customerCheckReportdwonload');
});