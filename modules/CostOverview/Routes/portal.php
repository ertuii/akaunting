<?php

use Illuminate\Support\Facades\Route;

/**
 * 'portal' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapPortalRoutes
 */

Route::group(['prefix' => 'cost-overviews', 'as' => 'portal.cost-overviews.'], function () {
    Route::get('{cost_overview}/print', 'Portal\CostOverviews@print')->name('print');
    Route::get('{cost_overview}/pdf', 'Portal\CostOverviews@pdf')->name('pdf');
});

Route::resource('cost-overviews', 'Portal\CostOverviews', ['as' => 'portal', 'only' => ['index', 'show']]);
