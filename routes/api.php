<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\AuthController as AuthController;
use App\Http\Controllers\API\v1\UsersController;
use App\Http\Controllers\API\v1\MetersController;
use App\Http\Controllers\API\v1\CustomersController;
use App\Http\Controllers\API\v1\MeterTypesController;
use App\Http\Controllers\API\v1\RatesController;
use App\Http\Controllers\API\v1\TenantsController;
use App\Http\Controllers\API\v1\ConsumptionsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

// user  routes
Route::resource('users', UsersController::class)->only([
    'index', 'store', 'show', 'update', 'destroy'
]);
Route::post('users/profile', [UsersController::class, 'profile']);
Route::post('users/change_password', [UsersController::class, 'changePassword']);


//meter  routes
Route::resource('meters', MetersController::class)->only([
    'index', 'store', 'show', 'update', 'destroy'
]);

//customers  routes
Route::resource('customers', CustomersController::class)->only([
    'index', 'store', 'show', 'update', 'destroy'
]);

//rates  routes
Route::resource('rates', RatesController::class)->only([
    'index', 'store', 'show', 'update', 'destroy'
]);


//tenants  routes
Route::resource('tenants', TenantsController::class)->only([
    'index', 'store', 'show', 'update', 'destroy'
]);

//meter type  routes
Route::resource('meter_types', MeterTypesController::class)->only([
    'index', 'store', 'show', 'update', 'destroy'
]);


//billings  routes ( consumption )
Route::resource('billings', BillingsController::class)->only([
    'index',
]);

//consumptions  routes ( consumption )
Route::resource('consumptions', ConsumptionsController::class)->only([
    'index',
]);

