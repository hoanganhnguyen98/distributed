<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Schedule;
use App\Model\Employee;
use Carbon\Carbon;

class ScheduleController extends BaseController
{
    public function getSchedule(Request $request)
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

        $employee = Employee::where('employee_id', $employee_id)->first();

        if (!$employee) {
            return $this->sendError('Nhân viên chưa tham gia vào hệ thống Xử lý công việc', 403);
        }

        $id = $employee->id;
        $schedule = Schedule::where('pending_ids', 'like', '%,'. $id . ',%')->get();

        return $this->sendResponse($schedule);
    }

    public function detail(Request $request)
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
}
