<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('user', 'Dashboard\UserController');
Route::resource('category', 'Dashboard\CategoryController');
Route::resource('product', 'Dashboard\ProductController');
Route::resource('product_Detail', 'Dashboard\ProductDetailController');
Route::resource('order', 'Dashboard\OrderController');
Route::resource('client', 'Dashboard\ClientController');
Route::resource('order_Detail', 'Dashboard\OrderDetailController');
Route::resource('report', 'Dashboard\ReportController');
Route::resource('expense', 'Dashboard\ExpenseController');
Route::resource('price', 'Dashboard\PriceController');
Route::resource('setting', 'Dashboard\SettingController');
Route::get('control', 'Dashboard\ControlController@index');
//Route::resource('product_detail', PhotoController::class);
