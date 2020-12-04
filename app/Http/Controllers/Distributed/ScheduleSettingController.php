<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Task;
use App\Model\TaskType;
use App\Model\Employee;
use App\Model\History;
use App\Model\ScheduleSetting;
use App\Model\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Distributed\HistoryController;

class ScheduleSettingController extends BaseController
{
    public function create(Request $request)
    {
        $apiToken = $request->header('api-token');
        $projectType = $request->header('project-type');

        $verifyApiToken = $this->verifyApiToken($apiToken, $projectType);

        if(empty($verifyApiToken)) {
            return $this->sendError('Đã có lỗi xảy ra từ khi gọi api verify token', 401);
        } else {
            $statusCode = $verifyApiToken['code'];

            if ($statusCode != 200) {
                return $this->sendError($message, $th->getCode());
            }
        }

        try {
            DB::beginTransaction();

            $rules = [
                'month' => ['required', 'numeric', 'min:1', 'max:12'],
                'year' => ['required', 'numeric', 'min:2020'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 404);
            }

            $setting = ScheduleSetting::where([['month', $request->get('month')], ['year', $request->get('year')]])->first();

            if (!$setting) {
                return $this->sendError('Lịch làm việc của tháng '. $request->get('month') . ' năm '. $request->get('year') . ' chưa được cấu hình.', 400);
            }

            $off_saturday = $setting->off_saturday;
            $off_sunday = $setting->off_sunday;
            $off_days = $setting->off_days;

            $month = $request->get('month');
            $year = $request->get('year');

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            for ($i=1; $i <= $days; $i++) {
                $schedule = Schedule::where([['day', $i], ['month', $month], ['year', $year]])->first();

                if($schedule) {
                    $schedule->delete();
                }

                $date = (string) $year . '-' . $month . '-' . $i;
                $dayOfWeek = $this->getDayofWeek($date);

                if ((in_array($i, $off_days)) || ($dayOfWeek == 'Saturday' && $off_saturday) || ($dayOfWeek == 'Sunday' && $off_sunday)) {
                    $off = true;
                } else {
                    $off = false;
                }

                Schedule::create([
                    'day' => $i,
                    'month' => $month,
                    'year' => $year,
                    'absent_ids' => [],
                    'off' => $off
                ]);
            }

            DB::commit();

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError('Có lỗi khi tạo lịch làm việc', 500);
        }
    }

    public function getDayofWeek($date)
    {
        $unixTimestamp = strtotime($date);

        return date("l", $unixTimestamp);
    }

    public function set(Request $request)
    {
        $apiToken = $request->header('api-token');
        $projectType = $request->header('project-type');

        $verifyApiToken = $this->verifyApiToken($apiToken, $projectType);

        if(empty($verifyApiToken)) {
            return $this->sendError('Đã có lỗi xảy ra từ khi gọi api verify token', 401);
        } else {
            $statusCode = $verifyApiToken['code'];

            if ($statusCode != 200) {
                return $this->sendError($message, $th->getCode());
            }
        }

        try {
            DB::beginTransaction();

            $rules = [
                'month' => ['required', 'numeric', 'min:1', 'max:12'],
                'year' => ['required', 'numeric', 'min:2020'],
                'off_saturday' => ['required', 'numeric', 'min:0', 'max:1'],
                'off_sunday' => ['required', 'numeric', 'min:0', 'max:1'],
                'off_days' => ['required', 'array']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 404);
            }

            $setting = ScheduleSetting::where([['month', $request->get('month')], ['year', $request->get('year')]])->first();

            if ($setting) {
                return $this->sendError('Lịch làm việc của tháng '. $request->get('month') . ' năm '. $request->get('year') . ' đã được cấu hình.', 400);
            }

            ScheduleSetting::create([
                'month' => $request->get('month'),
                'year' => $request->get('year'),
                'off_saturday' => ((int) $request->get('off_saturday')) == 1 ? true : false,
                'off_sunday' => ((int) $request->get('off_sunday')) == 1 ? true : false,
                'off_days' => $request->get('off_days')
            ]);

            DB::commit();

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError('Có lỗi khi cấu hình lịch làm việc', 500);
        }
    }

    public function update(Request $request)
    {
        $apiToken = $request->header('api-token');
        $projectType = $request->header('project-type');

        $verifyApiToken = $this->verifyApiToken($apiToken, $projectType);

        if(empty($verifyApiToken)) {
            return $this->sendError('Đã có lỗi xảy ra từ khi gọi api verify token', 401);
        } else {
            $statusCode = $verifyApiToken['code'];

            if ($statusCode != 200) {
                return $this->sendError($message, $th->getCode());
            }
        }

        try {
            DB::beginTransaction();

            $rules = [
                'id' => ['required'],
                'off_saturday' => ['bool'],
                'off_sunday' => ['bool'],
                'off_days' => ['array']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 400);
            }

            $setting = ScheduleSetting::where('id', $request->get('id'))->first();

            if (!$setting) {
                return $this->sendError('Không tìm thấy cấu hình của lịch làm việc hợp lệ', 404);
            }

            $updates = ['off_saturday', 'off_sunday'];

            foreach ($updates as $update) {
                if ($request->get($update)) {
                    $setting->{$update} = ((int) $request->get($update)) == 1 ? true : false;
                }
            }

            if ($request->get('off_days')) {
                $setting->off_days = $request->get('off_days');
            }

            $setting->save();

            DB::commit();

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError('Có lỗi khi cập nhật cấu hình lịch làm việc', 500);
        }
    }

    public function listing(Request $request)
    {
        $apiToken = $request->header('api-token');
        $projectType = $request->header('project-type');

        $verifyApiToken = $this->verifyApiToken($apiToken, $projectType);

        if(empty($verifyApiToken)) {
            return $this->sendError('Đã có lỗi xảy ra từ khi gọi api verify token', 401);
        } else {
            $statusCode = $verifyApiToken['code'];

            if ($statusCode != 200) {
                return $this->sendError($message, $th->getCode());
            }
        }

        $listing = ScheduleSetting::all();

        return $this->sendResponse($listing);
    }
}
