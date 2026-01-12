<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware applied via Service Provider
 * 
 * @see \Modules\CostOverview\Providers\Main::loadRoutes
 */

Route::group(['as' => 'cost-overviews.'], function () {
    Route::get('/', 'CostOverviews@index')->name('index');
    Route::get('{customer_id}', 'CostOverviews@show')->name('show');
    Route::get('{customer_id}/email', 'CostOverviews@email')->name('email');
    Route::get('{customer_id}/pdf', 'CostOverviews@pdf')->name('pdf');
    Route::get('{customer_id}/print', 'CostOverviews@print')->name('print');
});
