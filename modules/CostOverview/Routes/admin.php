<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapAdminRoutes
 */

Route::group(['prefix' => 'cost-overviews', 'as' => 'cost-overviews.'], function () {
    Route::get('{cost_overview}/sent', 'Admin\CostOverviews@markSent')->name('sent');
    Route::get('{cost_overview}/approved', 'Admin\CostOverviews@markApproved')->name('approved');
    Route::get('{cost_overview}/email', 'Admin\CostOverviews@email')->name('email');
    Route::get('{cost_overview}/print', 'Admin\CostOverviews@print')->name('print');
    Route::get('{cost_overview}/pdf', 'Admin\CostOverviews@pdf')->name('pdf');
    Route::get('{cost_overview}/duplicate', 'Admin\CostOverviews@duplicate')->name('duplicate');
    Route::post('{cost_overview}/convert-to-invoice', 'Admin\CostOverviews@convertToInvoice')->name('convert-to-invoice');
});

Route::resource('cost-overviews', 'Admin\CostOverviews', ['middleware' => ['date.format', 'money', 'dropzone']]);
