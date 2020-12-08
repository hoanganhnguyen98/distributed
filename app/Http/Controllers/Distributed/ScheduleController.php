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
                    $off_days[] = (int) $value->day;
                }
            } else {
                if ($employee_id == $absent_ids) {
                    $off_days[] = (int) $value->day;
                }
            }
        }

        sort($off_days);

        return $this->sendResponse($off_days);
    }

    public function getOffDaysInMonth(Request $request)
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

        $month = date('m');
        $year = date('Y');

        $setting = ScheduleSetting::where([['month', $month], ['year', $year]])->first();

        if (!$setting) {
            return $this->sendError('Lịch làm việc của tháng '. $month . ' năm '. $year . ' chưa được cấu hình.', 404);
        }

        $off_saturday = $setting->off_saturday;
        $off_sunday = $setting->off_sunday;
        $off_days = $setting->off_days;

        $offDaysInMonth = [];

        if ($off_saturday) {
            $off_weekend = $this->getDaysWeekend('Saturday');

            foreach ($off_weekend as $key => $value) {
                $offDaysInMonth[] = (int) $value;
            }
        }

        if ($off_sunday) {
            $off_weekend = $this->getDaysWeekend('Sunday');

            foreach ($off_weekend as $key => $value) {
                $offDaysInMonth[] = (int) $value;
            }
        }

        if ($off_days) {
            if (strpos($off_days, ',') > 0) {
                foreach (explode(',', $off_days) as $key => $value) {
                    if (!in_array($value, $offDaysInMonth)) {
                        $offDaysInMonth[] = (int) $value;
                    }
                }
            } else {
                if (!in_array($off_days, $offDaysInMonth)) {
                    $offDaysInMonth[] = (int) $off_days;
                }
            }
        }

        sort($offDaysInMonth);

        return $this->sendResponse($offDaysInMonth);
    }

    public function daily(Request $request)
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

        $absents = [];
        if ($absent_ids) {
            if (strpos($absent_ids, ',' >  0)) {
                foreach (explode(',', $absent_ids) as $id) {
                    $user = userGetting($id, $apiToken, $projectType);

                    $absents[] = $user;
                }
            } else {
                $user = userGetting($absent_ids, $apiToken, $projectType);

                $absents[] = $user;
            }
        }

        return $this->sendResponse($absents);
    }

    public function userGetting($employee_id, $apiToken, $projectType)
    {
        $url = 'https://distributed.de-lalcool.com/api/user/'. $employee_id;

        $headers = [
            'token' => $apiToken,
            'project-type' => $projectType,
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->get($url, [
                'headers' => $headers,
            ]);
        } catch (\Throwable $th) {
            return null;
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            return null;
        }

        return $data['result'];
    }

    public function getDaysWeekend($weekend)
    {
        $month = date('m');
        $year = date('Y');
        $days = $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);

        $off_days = [];
        for ($i=1; $i <= $days; $i++) {
            $date = (string) $year . '-' . $month . '-' . $i;
            $unixTimestamp = strtotime($date);
            $dayOfWeek = date("l", $unixTimestamp);

            if ($dayOfWeek == $weekend) {
                $off_days[] = $i;
            }
        }

        return $off_days;
    }
}
