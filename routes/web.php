<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', function () {
    $posts = \App\User::get();
    return response()->json($posts);
});


Auth::routes();

Route::resource('report', 'Dashboard\ReportController');

//Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();
Route::get('{path}', 'HomeController@index')->where('path', '([A-z\d\-\/_.]+)?');


Route::get('/home', 'HomeController@index')->name('home');
