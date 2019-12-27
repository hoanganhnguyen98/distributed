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

// show main home page of website - customer can see
Route::get('/index', 'UserController@index')->name('index');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/lang/{locale}', 'UserController@lang');
Route::get('password/lang/{locale}', 'UserController@lang');
Route::get('password/reset/lang/{locale}', 'UserController@lang');

// Auth::routes();

// gọi ra trang view demo-pusher.blade.php
 Route::get('/demo-pusher','FrontEndController@getPusher')->name('demo-pusher');
// Truyển message lên server Pusher
 Route::get('/fire-event','FrontEndController@fireEvent')->name('fire-event');

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
Route::post('edit-bill', 'Receptionist\BillController@editBillForm')->name('edit-bill');
Route::get('pay-bill-{table_id}', 'Receptionist\BillController@showPayBillForm')->name('pay-bill-form');
Route::get('pay-{table_id}/{type}', 'Receptionist\BillController@payBill')->name('pay');
Route::get('export-bill-{table_id}', 'Receptionist\BillController@exportRedBill')->name('export-bill');
Route::get('bill-list', 'Receptionist\BillController@showBillList')->name('bill-list');
// bill detail
Route::get('add-bill-detail-{table_id}', 'Waiter\BillDetailController@showAddBillDetailForm')->name('add-bill-detail-form');
Route::post('add-bill-detail', 'Waiter\BillDetailController@addBillDetail')->name('add-bill-detail');

//kitchen management
Route::get('table-detail-{table_id}', 'KitchenManager\OrderController@showTableDetail')->name('table-detail');
Route::get('prepare-order-{id}', 'KitchenManager\OrderController@prepareOrder')->name('prepare-order');
Route::get('delete-order-{id}', 'KitchenManager\OrderController@deleteOrder')->name('delete-order');
Route::get('confirm-order-{id}', 'KitchenManager\OrderController@confirmOrder')->name('confirm-order');

//accountant
Route::get('create-deposit', 'Accountant\DepositController@showCreateDepositForm')->name('create-deposit');
Route::post('create-deposit', 'Accountant\DepositController@create');
Route::get('repay-deposit', 'Accountant\DepositController@showRepayDepositForm')->name('repay-deposit');
Route::post('repay-deposit', 'Accountant\DepositController@repay');
Route::get('deposit-bill', 'Accountant\DepositController@showDepositList')->name('deposit-bill');


//Export excel
Route::get('export-bill', 'Accountant\Excel\BillController@export')->name('export-bill');
Route::get('export-food', 'Accountant\Excel\FoodController@export')->name('export-food');

// profile
Route::get('profile', 'ProfileController@showProfile')->name('profile');
Route::get('change-password', 'ProfileController@showChangePasswordForm')->name('change-password');
Route::post('change-password', 'ProfileController@changePassword');

//Forget password
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
