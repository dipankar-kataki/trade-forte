<?php

use App\Http\Controllers\BankAccounts\BankAccountController;
use App\Http\Controllers\Consignees\ConsigneeController;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\Declarations\DeclarationController;
use App\Http\Controllers\Exporters\ExporterController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\InvoiceItems\InvoiceItemsController;
use App\Http\Controllers\Module\ModuleController;
use App\Http\Controllers\Packaging\PackagingController;
use App\Http\Controllers\ShippingAddress\ShippingAddressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("login", [UserController::class, "login"])->name("login");
Route::post('forgotpassword', [UserController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('resetpassword', [UserController::class, 'resetPassword'])->name('resetpassword');

Route::group(["middleware" => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::post('create', [UserController::class, 'create']);
        Route::get('get/list', [UserController::class, 'index']);
        Route::get('get/{id}', [UserController::class, 'show']);
        Route::put('update/{id}', [UserController::class, 'update']);
        Route::delete('delete/{id}', [UserController::class, 'destroy']);
        Route::delete('changePassword', [UserController::class, 'changePassword']);
    });
    Route::group(['prefix' => 'exporters'], function () {
        Route::post('create', [ExporterController::class, 'create']);
        Route::get('get/list', [ExporterController::class, 'index']);
        Route::get('get/{id}', [ExporterController::class, 'show']);
        Route::put('update/{exporterId}', [ExporterController::class, 'update']);
        Route::delete('delete/{exporterId}', [ExporterController::class, 'destroy']);
    });
    Route::group(['prefix' => 'bankaccount'], function () {
        Route::post('create', [BankAccountController::class, 'create']);
        Route::get('get/list', [BankAccountController::class, 'index']);
        Route::get('get/{id}', [BankAccountController::class, 'show']);
        Route::put('update/{id}', [BankAccountController::class, 'update']);
        Route::delete('delete/{id}', [BankAccountController::class, 'destroy']);
    });
    Route::group(['prefix' => 'consignees'], function () {
        Route::post('create', [ConsigneeController::class, 'create']);
        Route::get('get/list', [ConsigneeController::class, 'index']);
        Route::get('get/{id}', [ConsigneeController::class, 'show']);
        Route::put('update/{id}', [ConsigneeController::class, 'update']);
        Route::delete('delete/{id}', [ConsigneeController::class, 'destroy']);
    });
    Route::group(['prefix' => 'country'], function () {
        Route::post('create', [CountryController::class, 'create']);
        Route::get('get/list', [CountryController::class, 'index']);
        Route::delete('delete/{id}', [CountryController::class, 'destroy']);
    });
    Route::group(['prefix' => 'declarations'], function () {
        Route::post('create', [DeclarationController::class, 'create']);
        Route::get('get/list', [DeclarationController::class, 'index']);
        Route::get('get/{id}', [DeclarationController::class, 'show']);
        Route::put('update/{id}', [DeclarationController::class, 'update']);
        Route::delete('delete/{id}', [DeclarationController::class, 'destroy']);
    });
    Route::group(['prefix' => 'invoice'], function () {
        Route::post('create', [InvoiceController::class, 'create']);
        Route::get('get/list', [InvoiceController::class, 'index']);
        Route::get('get/{id}', [InvoiceController::class, 'show']);
        Route::put('update/{id}', [InvoiceController::class, 'update']);
        Route::delete('delete/{id}', [InvoiceController::class, 'destroy']);
    });
    Route::group(['prefix' => 'invoiceitems'], function () {
        Route::post('create', [InvoiceItemsController::class, 'create']);
        Route::get('get/list', [InvoiceItemsController::class, 'index']);
        Route::get('get/{id}', [InvoiceItemsController::class, 'show']);
        // Route::put('update/{bankAccountId}', [InvoiceItemsController::class, 'update']);
        // Route::delete('delete/{bankAccountId}', [InvoiceItemsController::class, 'destroy']);
    });
    Route::group(['prefix' => 'modules'], function () {
        Route::post('create', [ModuleController::class, 'create']);
        Route::get('get/list', [ModuleController::class, 'index']);
        Route::get('get/{id}', [ModuleController::class, 'show']);
        Route::put('update/{moduleId}', [ModuleController::class, 'update']);
        Route::delete('delete/{moduleId}', [ModuleController::class, 'destroy']);
    });
    Route::group(['prefix' => 'packaging'], function () {
        Route::post('create', [PackagingController::class, 'create']);
        Route::get('get/list', [PackagingController::class, 'index']);
        Route::get('get/{id}', [PackagingController::class, 'show']);
        Route::put('update/{id}', [PackagingController::class, 'update']);
        Route::delete('delete/{id}', [PackagingController::class, 'destroy']);
    });
    Route::group(['prefix' => 'shipping'], function () {
        Route::post('create', [ShippingAddressController::class, 'create']);
        Route::get('get/list', [ShippingAddressController::class, 'index']);
        Route::get('get/{id}', [ShippingAddressController::class, 'show']);
        Route::put('update/{id}', [ShippingAddressController::class, 'update']);
        Route::delete('delete/{id}', [ShippingAddressController::class, 'destroy']);
    });
    Route::group(['prefix' => 'useractivity'], function () {
        Route::get('get/list', [ShippingAddressController::class, 'index']);
        Route::get('get/{id}', [ShippingAddressController::class, 'show']);
    });
});

