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
Route::get('task/incident-listing', 'Distributed\TaskController@incidentListing');
Route::get('task/employee-listing', 'Distributed\TaskController@getEmployeeListing');
Route::get('task/detail', 'Distributed\TaskController@detail');

// vien tro
Route::get('support/listing', 'Distributed\SupportController@listing');
Route::post('support/create', 'Distributed\SupportController@create');

// bao cao
Route::get('report/listing', 'Distributed\ReportController@listing');
Route::post('report/create', 'Distributed\ReportController@create');
Route::post('report/accept', 'Distributed\ReportController@accept');
Route::post('report/reject', 'Distributed\ReportController@reject');

// nhan vien
Route::post('employee/login', 'Distributed\EmployeeController@detail');
Route::get('employee/listing', 'Distributed\EmployeeController@listing');
Route::post('employee/active', 'Distributed\EmployeeController@active');

// lich lam viec
Route::get('schedule/detail-day', 'Distributed\ScheduleController@detail');
Route::get('schedule/detail-employee', 'Distributed\ScheduleController@getSchedule');

// generate data
Route::get('generate/employee', 'Distributed\GenerateController@generateEmployee');
Route::get('generate/schedule', 'Distributed\GenerateController@generateSchedule');

// external
Route::get('external/report-listing', 'Distributed\ExternalController@reportListing');
Route::get('external/task-by-incident', 'Distributed\ExternalController@getTaskByIncidentId');
Route::get('external/user-tasks', 'Distributed\ExternalController@getUserTasks');

Route::get('incident/listing', 'Distributed\IncidentController@listing');

// task-type
Route::post('task-type/create', 'Distributed\TaskTypeController@create');
Route::put('task-type/update', 'Distributed\TaskTypeController@update');
Route::delete('task-type/delete', 'Distributed\TaskTypeController@delete');
Route::get('task-type/listing', 'Distributed\TaskTypeController@listing');

Route::post('schedule-setting/set', 'Distributed\ScheduleSettingController@set');
Route::put('schedule-setting/update', 'Distributed\ScheduleSettingController@update');
Route::get('schedule-setting/listing', 'Distributed\ScheduleSettingController@listing');











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
