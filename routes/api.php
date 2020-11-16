<?php

use Illuminate\Http\Request;

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

// xu ly cong viec
Route::post('task/handler', 'Distributed\TaskController@handler');
Route::get('task/listing', 'Distributed\TaskController@listing');
Route::get('task/detail', 'Distributed\TaskController@detail');

// vien tro
Route::get('support/listing', 'Distributed\SupportController@listing');

// bao cao
Route::get('report/listing', 'Distributed\ReportController@listing');

Route::post('report/accept', 'Distributed\ReportController@accept');
Route::post('report/reject', 'Distributed\ReportController@reject');

// nhan vien
Route::get('employee/detail', 'Distributed\EmployeeController@detail');












//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::post('user/update', 'API\UserController@update');
Route::post('user/changepassword', 'API\UserController@changePassword');
Route::post('user/forgetpassword', 'API\UserController@forgetPassword');
Route::post('user/resetpassword', 'API\UserController@resetPassword');

Route::get('food/index/{source}', 'API\FoodController@index');

Route::post('addtocart', 'API\BillController@addToCart');
Route::get('cart/currentcart/{user_id}', 'API\BillController@getCurrentCart');
Route::get('cart/currentcart/remove/{id}', 'API\BillController@removeCart');
Route::post('cart/currentcart/order', 'API\BillController@orderNow');
Route::get('cart/history/{user_id}', 'API\BillController@getHistory');
Route::get('cart/history/detail/{bill_id}', 'API\BillController@getHistoryDetail');
