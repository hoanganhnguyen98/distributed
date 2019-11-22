<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can create web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');
Route::get('/lang/{locale}', 'HomeController@lang');
Route::get('password/lang/{locale}', 'HomeController@lang');
Route::get('password/reset/lang/{locale}', 'HomeController@lang');

// Auth::routes();

// Login - Logout
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

// create
Route::get('create-account', 'Admin\AccountController@showCreateAccountForm')->name('create-account');
Route::post('create-account', 'Admin\AccountController@createAccount');

Route::get('create-food', 'Admin\FoodController@showCreateFoodForm')->name('create-food');
Route::post('create-food', 'Admin\FoodController@createFood');

Route::get('create-bill', 'Receptionist\BillController@showCreateBillForm')->name('create-bill');
Route::post('create-bill', 'Receptionist\BillController@createBill');

// show list
Route::get('account-list', 'Admin\AccountController@showAccountList')->name('account-list');
Route::get('food-list', 'Admin\FoodController@showFoodList')->name('food-list');
// Route::get('bill-list', 'Admin\AccountController@showAccountList')->name('bill-list');

//Forget password
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
