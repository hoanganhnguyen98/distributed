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

// account
Route::get('create-account', 'Admin\AccountController@showCreateAccountForm')->name('create-account');
Route::post('create-account', 'Admin\AccountController@createAccount');
Route::get('account-list', 'Admin\AccountController@showAccountList')->name('account-list');
Route::get('account-detail-{user_id}', 'Admin\AccountController@showAccountDetail')->name('account-detail');
Route::post('account-change', 'Admin\AccountController@changeImage')->name('account-change');
Route::post('account-edit', 'Admin\AccountController@editAccount')->name('account-edit');
Route::get('account-delete-{user_id}', 'Admin\AccountController@deleteAccount')->name('account-delete');

// food
Route::get('create-food', 'Admin\FoodController@showCreateFoodForm')->name('create-food');
Route::post('create-food', 'Admin\FoodController@createFood');
Route::get('food-list', 'Admin\FoodController@showFoodList')->name('food-list');
Route::get('food-edit-{id}', 'Admin\FoodController@showEditFoodForm')->name('food-edit');
Route::post('food-edit-info', 'Admin\FoodController@editFoodInformation')->name('food-edit-info');
Route::post('food-change-image', 'Admin\FoodController@changeFoodImage')->name('food-change-image');
Route::get('food-delete-{id}', 'Admin\FoodController@deleteFood')->name('food-delete');

// bill
Route::get('create-bill-{table_id}', 'Receptionist\BillController@showCreateBillForm')->name('create-bill-form');
Route::post('create-bill', 'Receptionist\BillController@createBill')->name('create-bill');
Route::get('cancel-create-bill-{table_id}', 'Receptionist\BillController@cancelCreateBill')->name('cancel-create-bill');
Route::get('bill-list', 'Receptionist\BillController@showBillList')->name('bill-list');

// profile
Route::get('profile', 'ProfileController@showProfile')->name('profile');
Route::get('change-password', 'ProfileController@showChangePasswordForm')->name('change-password');
Route::post('change-password', 'ProfileController@changePassword');

//Forget password
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
