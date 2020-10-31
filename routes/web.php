<?php

use App\Http\Middleware\Authcheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryController;


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

// Auth::routes();
Route::prefix('app')->middleware([AuthCheck::class])->group(function () {
    Route::get('/get_tag', 'TagController@index');
    Route::post('/create_tag', 'TagController@store');
    Route::put('/update_tag/{id}', 'TagController@update');
    Route::delete('/delete_tag/{id}', 'TagController@destroy');
    Route::get('/findtag', 'TagController@search');
    Route::get('/get_category', 'CategoryController@index');
    Route::post('/create_category', 'CategoryController@store');
    Route::put('//update_category/{id}', 'CategoryController@update');
    Route::delete('/delete_category/{id}', 'CategoryController@destroy');
    Route::get('/get_user', 'UserController@index');
    Route::post('/create_user', 'UserController@store');
    Route::put('/update_user/{id}', 'UserController@update');
    Route::delete('/delete_user/{id}', 'UserController@destroy');
    Route::get('/finduser', 'UserController@search');
    Route::post('/login', 'LoginController@adminlogin');
    Route::get('/get_role', 'RoleController@index');
    Route::post('/create_role', 'RoleController@store');
    Route::post('/assign_role', 'RoleController@assignrole');
    Route::put('/update_role/{id}', 'RoleController@update');
    Route::delete('/delete_role/{id}', 'RoleController@destroy');
    Route::get('/findrole', 'RoleController@search');
});
// Route::post('app/create_tag', 'TestController@test');

Route::get('/', 'LoginController@index');

Route::get('/logout', 'LoginController@logout');
Route::get('{slug}', 'LoginController@index');
// Route::any('{slug}', function () {
//     return view('welcome');
// });
