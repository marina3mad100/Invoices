<?php


use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;








Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
    
])->group(function () {

    Route::get('/', function () {
        return view('invoicesSystem.pages.dashboard');
    })->name('dashboard');

	Route::group(['prefix'     => 'users','as'=> 'users.',], function () {
		Route::get('/search',  [UserController::class, "search"])->name('search');
		Route::get('/',  [UserController::class, "index"])->name('all');
		Route::get('/create',  [UserController::class, "create"])->name('create')->middleware('CheckHasPermission:add user');
		Route::Post('/store',  [UserController::class, "store"])->name('store')->middleware('CheckHasPermission:add user');
		Route::get('/edit/{id}',  [UserController::class, "edit"])->name('edit')->middleware('CheckHasPermission:edit user');
		Route::POST('/update/{id}',  [UserController::class, "update"])->name('update')->middleware('CheckHasPermission:edit user');
		Route::get('/destroy/{id}',  [UserController::class, "destroy"])->name('destroy')->middleware('CheckHasPermission:delete user');

	});

	Route::group(['prefix'     => 'invoices','as'=> 'invoices.',], function () {
		Route::get('/search',  [InvoiceController::class, "search"])->name('search');
		Route::get('/',  [InvoiceController::class, "index"])->name('all');
		Route::get('/create',  [InvoiceController::class, "create"])->name('create')->middleware('CheckHasPermission:add invoice');
		Route::Post('/store',  [InvoiceController::class, "store"])->name('store')->middleware('CheckHasPermission:add invoice');
		Route::get('/edit/{id}',  [InvoiceController::class, "edit"])->name('edit')->middleware('CheckHasPermission:edit invoice');
		Route::POST('/update/{id}',  [InvoiceController::class, "update"])->name('update')->middleware('CheckHasPermission:edit invoice');
		Route::get('/destroy/{id}',  [InvoiceController::class, "destroy"])->name('destroy')->middleware('CheckHasPermission:delete invoice');
	});	


	Route::get('/logs',  [ActivityLogController::class, "logs"])->name('logs')->middleware('CheckHasPermission:admin only');
	Route::get('/destroy/{id}',  [ActivityLogController::class, "destroy"])->name('logs.destroy')->middleware('CheckHasPermission:admin only');

  

});
