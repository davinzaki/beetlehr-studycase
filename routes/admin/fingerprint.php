<?php

use App\Http\Controllers\Fingerprint\FingerprintController;


/*
|--------------------------------------------------------------------------
| Fingerprint Routes
|--------------------------------------------------------------------------
|
| Here is where you can register finger related routes for your application.
|
*/



Route::prefix('fingerprint')->name('fingerprint.')->group(function () {
    Route::controller(FingerprintController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('get-data', 'getData')->name('getdata');
        Route::post('create', 'createData')->name('create');
        Route::post('{id}/update', 'updateData')->name('update');
        Route::delete('{id}/delete', 'deleteData')->name('delete');
    });
});
