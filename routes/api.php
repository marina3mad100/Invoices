<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\InvoiceController;




Route::group(['middleware' => ['cors']], function () {
    Route::post('loginUser',  [LoginController::class, "login"]);

});
Route::group(['middleware' => ['cors','auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return \Auth::user();
    });
    Route::post('logout',  [LoginController::class, "logout"]);
});    



Route::group(['prefix'     => 'users', 'middleware' => ['cors','auth:sanctum']], function () {
    Route::get('all',  [UserController::class, "index"])->middleware('CheckHasPermission:view all users');
    Route::get('show/{id}',  [UserController::class, "show"])->middleware('CheckHasPermission:view all users');
    Route::get('destroy/{id}',  [UserController::class, "destroy"])->middleware('CheckHasPermission:delete user');

});

Route::group(['prefix'     => 'invoices', 'middleware' => ['cors','auth:sanctum']], function () {
    Route::get('all',  [InvoiceController::class, "index"])->middleware('CheckHasPermission:view all invoices');
    Route::get('show/{id}',  [InvoiceController::class, "show"])->middleware('CheckHasPermission:view all invoices');
    Route::get('destroy/{id}',  [InvoiceController::class, "destroy"])->middleware('CheckHasPermission:delete invoice');

    Route::post('store',  [InvoiceController::class, "store"])->middleware('CheckHasPermission:add invoice');
    Route::post('update/{id}',  [InvoiceController::class, "update"])->middleware('CheckHasPermission:edit invoice');


});


