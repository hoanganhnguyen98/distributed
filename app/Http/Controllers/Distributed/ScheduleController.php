<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Schedule;
use App\Model\Employee;
use App\Model\ScheduleSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Distributed\TaskController;

class ScheduleController extends BaseController
{
    public function absentRequest(Request $request)
    {
        $apiToken = $request->header('api-token');
        $projectType = $request->header('project-type');

        $verifyApiToken = $this->verifyApiToken($apiToken, $projectType);

        if(empty($verifyApiToken)) {
            return $this->sendError('Đã có lỗi xảy ra từ khi gọi api verify token', 401);
        } else {
            $statusCode = $verifyApiToken['code'];

            if ($statusCode != 200) {
                return $this->sendError($verifyApiToken['message'], $statusCode);
            }
        }

        $employee_id = $request->get('absent_id');

        if (!$employee_id) {
            return $this->sendError("Định danh nhân viên absent_id đang trống", 400);
        }

        $validEmployee = (new TaskController)->userChecking($employee_id, $apiToken, $projectType);

        if (!$validEmployee) {
            return $this->sendError("Không tìm thấy nhân viên nào hợp lệ", 404);
        }

        try {
            DB::beginTransaction();

            $rules = [
                'day' => ['required'],
                'month' => ['required'],
                'year' => ['required'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Thời gian yêu cầu chưa hợp lệ', 400);
            }

            $day = (int) $request->get('day');
            $month = (int) $request->get('month');
            $year = (int) $request->get('year');

            $days = $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);

            if ($day < 0 || $day > $days) {
                return $this->sendError('Giá trị của ngày chưa hợp lệ', 400);
            }

            $setting = ScheduleSetting::where([['month', $month], ['year', $year]])->first();

            if (!$setting) {
                return $this->sendError('Lịch làm việc của tháng '. $month . ' năm '. $year . ' chưa được cấu hình.', 404);
            }

            $schedule = Schedule::where([['day', $day], ['month', $month], ['year', $year]])->first();

            if (!$schedule) {
                return $this->sendError('Không tồn tại lịch làm việc của ngày ' . $day, 404);
            }

            $absent_ids = $schedule->absent_ids;

            if ($absent_ids) {
                $new_absent_ids = $absent_ids . ',' . $employee_id;
            } else {
                $new_absent_ids = $employee_id;;
            }

            $schedule->absent_ids = $new_absent_ids;
            $schedule->save();

            DB::commit();

            return $this->sendResponse();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->sendError('Đã có lỗi xảy ra khi xử lý nghỉ phép cho nhân viên', 500);
        }
    }

    public function getOffDaysOfEmployee(Request $request)
    {
        $apiToken = $request->header('api-token');
        $projectType = $request->header('project-type');

        $verifyApiToken = $this->verifyApiToken($apiToken, $projectType);

        if(empty($verifyApiToken)) {
            return $this->sendError('Đã có lỗi xảy ra từ khi gọi api verify token', 401);
        } else {
            $statusCode = $verifyApiToken['code'];

            if ($statusCode != 200) {
                return $this->sendError($verifyApiToken['message'], $statusCode);
            }
        }

        $id = $request->get('id');
        $employee_id = $id ? $id : $verifyApiToken['id'];

        $validEmployee = (new TaskController)->userChecking($employee_id, $apiToken, $projectType);

        if (!$validEmployee) {
            return $this->sendError("Không tìm thấy nhân viên nào hợp lệ", 404);
        }

        $month = date('m');
        $year = date('Y');

        $setting = ScheduleSetting::where([['month', $month], ['year', $year]])->first();

        if (!$setting) {
            return $this->sendError('Lịch làm việc của tháng '. $month . ' năm '. $year . ' chưa được cấu hình.', 404);
        }

        $schedules = Schedule::where([['absent_ids', 'like', '%'. $employee_id . '%'], ['month', $month], ['year', $year]])->get();

        $off_days = [];
        foreach ($schedules as $key => $value) {
            $absent_ids = $value->absent_ids;

            if (strpos($absent_ids, ',') > 0) {
                if (in_array($employee_id, explode(',', $absent_ids))) {
                    $off_days[] = $value->day;
                }
            } else {
                if ($employee_id == $absent_ids) {
                    $off_days[] = $value->day;
                }
            }
        }

        return $this->sendResponse($off_days);
    }

    public function getOffDaysInMonth(Request $request)
    {
        $day = $request->get('day');
        $month = $request->get('month');
        $year = $request->get('year');

        if (!$day || !$month || !$year) {
            return $this->sendError('Cần có đủ 3 trường ngày, tháng, năm', 400);
        }

        $schedule = Schedule::where([['day', $day], ['month', $month], ['year', $year]])->first();

        $data = [];
        if ($schedule) {
            $employee_ids = $schedule->employee_ids;

            if (strlen($employee_ids) > 1) {
                $ids = array_slice(explode(',', $employee_ids), 1, -1);

                foreach ($ids as $key => $id) {
                    $employee = Employee::where('employee_id', $id)->first();

                    if ($employee) {
                        $data[] = [
                            'employee_id' => $employee->employee_id,
                            'name' => $employee->name
                        ];
                    }
                }
            }
        }

        return $this->sendResponse($data);
    }

    public function daily(Request $request)
    {

    }
}
