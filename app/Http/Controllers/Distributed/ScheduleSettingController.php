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
    public function create($month, $year)
    {
        $setting = ScheduleSetting::where([['month', $month], ['year', $year]])->first();

        $off_saturday = $setting->off_saturday;
        $off_sunday = $setting->off_sunday;
        $off_days = $setting->off_days;

        if ($setting->off_days) {
            if (strpos($setting->off_days, ',') < 0) {
                $off_days = [$setting->off_days];
            } else {
                $off_days = explode(',', $setting->off_days);
            }
        } else {
            $off_days = [];
        }

        $days = $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);

        for ($i=1; $i <= $days; $i++) {
            $schedule = Schedule::where([['day', $i], ['month', $month], ['year', $year]])->first();

            $absent_ids = null;
            if($schedule) {
                $absent_ids = $schedule->absent_ids;
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
                'absent_ids' => $absent_ids,
                'off' => $off
            ]);
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
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $this->logging(
                    'Cài đặt lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Cấu hình lịch làm việc',
                    'activity'
                );

                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 404);
            }

            $month = $request->get('month');
            $year = $request->get('year');
            $off_days = $request->get('off_days');

            if ($off_days) {
                if (strpos($off_days, ',') < 0) {
                    if ((int) $request->get('off_days') == null) {
                        $this->logging(
                            'Cài đặt lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                            $verifyApiToken['id'],
                            $projectType,
                            'failure',
                            'Cấu hình lịch làm việc',
                            'activity'
                        );

                        return $this->sendError('Chuỗi ngày nghỉ off_days chưa hợp lệ', 400);
                    }
                } else {
                    $days = explode(',', $request->get('off_days'));

                    $daysInMonth = $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);

                    if (!empty($days)) {
                        foreach ($days as $day) {
                            // chứa 2 dấu phẩy liên tiếp
                            if ((int) $day == null) {
                                $this->logging(
                                    'Cài đặt lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                                    $verifyApiToken['id'],
                                    $projectType,
                                    'failure',
                                    'Cấu hình lịch làm việc',
                                    'activity'
                                );

                                return $this->sendError('Chuỗi ngày nghỉ off_days chưa hợp lệ', 400);
                            }

                            // chứa ngày không có trong tháng
                            if ((int) $day > $daysInMonth || (int) $day < 1) {
                                $this->logging(
                                    'Cài đặt lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                                    $verifyApiToken['id'],
                                    $projectType,
                                    'failure',
                                    'Cấu hình lịch làm việc',
                                    'activity'
                                );

                                return $this->sendError('Chuỗi ngày nghỉ off_days chứa ngày không hợp lệ', 400);
                            }
                        }
                    } else {
                        // chứa toàn dấu phẩy và space
                        $this->logging(
                            'Cài đặt lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                            $verifyApiToken['id'],
                            $projectType,
                            'failure',
                            'Cấu hình lịch làm việc',
                            'activity'
                        );

                        return $this->sendError('Chuỗi ngày nghỉ off_days chưa hợp lệ', 400);
                    }
                }
            } else {
                $off_days = null;
            }

            $setting = ScheduleSetting::where([['month', $request->get('month')], ['year', $request->get('year')]])->first();

            if ($setting) {
                $this->logging(
                    'Cài đặt lịch làm việc lỗi do đã được cài đặt từ trước',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Cấu hình lịch làm việc',
                    'activity'
                );

                return $this->sendError('Lịch làm việc của tháng '. $request->get('month') . ' năm '. $request->get('year') . ' đã được cấu hình.', 400);
            }

            ScheduleSetting::create([
                'month' => $request->get('month'),
                'year' => $request->get('year'),
                'off_saturday' => ((int) $request->get('off_saturday')) == 1 ? true : false,
                'off_sunday' => ((int) $request->get('off_sunday')) == 1 ? true : false,
                'off_days' => $off_days
            ]);

            $this->create((int) $request->get('month'), (int) $request->get('year'));

            DB::commit();

            $this->logging(
                'Cài đặt lịch làm việc thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Cấu hình lịch làm việc',
                'activity'
            );

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            $this->logging(
                'Cài đặt lịch làm việc lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Cấu hình lịch làm việc',
                'activity'
            );

            return $this->sendError('Có lỗi khi cấu hình lịch làm việc', 500);
        }
    }

    public function delete(Request $request)
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

            $id = $request->get('id');

            if (!$id) {
                $this->logging(
                    'Xóa lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Cấu hình lịch làm việc',
                    'activity'
                );

                return $this->sendError('Không có định danh cấu hình lịch làm việc', 400);
            }

            $setting = ScheduleSetting::where('id', $id)->first();

            if (!$setting) {
                $this->logging(
                    'Xóa lịch làm việc lỗi do không tìm thấy cài đặt lịch làm việc',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Cấu hình lịch làm việc',
                    'activity'
                );

                return $this->sendError('Không tìm thấy cấu hình lịch làm việc hợp lệ', 404);
            }

            $setting->delete();

            DB::commit();

            $this->logging(
                'Xóa lịch làm việc thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Cấu hình lịch làm việc',
                'activity'
            );

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            $this->logging(
                'Xóa lịch làm việc lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Cấu hình lịch làm việc',
                'activity'
            );

            return $this->sendError('Có lỗi khi xóa cấu hình lịch làm việc', 500);
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
                'off_saturday' => ['required', 'numeric', 'min:0', 'max:1'],
                'off_sunday' => ['required', 'numeric', 'min:0', 'max:1'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $this->logging(
                    'Cập nhật lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Cấu hình lịch làm việc',
                    'activity'
                );

                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 400);
            }

            $setting = ScheduleSetting::where('id', $request->get('id'))->first();

            if (!$setting) {
                $this->logging(
                    'Cập nhật lịch làm việc lỗi do không tìm thấy cài đặt trước đó',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Cấu hình lịch làm việc',
                    'activity'
                );

                return $this->sendError('Không tìm thấy cấu hình của lịch làm việc hợp lệ', 404);
            }

            $month = $setting->month;
            $year = $setting->year;
            $off_days = $request->get('off_days');

            if ($off_days) {
                if (strpos($off_days, ',') < 0) {
                    if ((int) $request->get('off_days') == null) {
                        $this->logging(
                            'Cập nhật lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                            $verifyApiToken['id'],
                            $projectType,
                            'failure',
                            'Cấu hình lịch làm việc',
                            'activity'
                        );

                        return $this->sendError('Chuỗi ngày nghỉ off_days chưa hợp lệ', 400);
                    }
                } else {
                    $days = explode(',', $request->get('off_days'));

                    $daysInMonth = $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);

                    if (!empty($days)) {
                        foreach ($days as $day) {
                            // chứa 2 dấu phẩy liên tiếp
                            if ((int) $day == null) {
                                $this->logging(
                                    'Cập nhật lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                                    $verifyApiToken['id'],
                                    $projectType,
                                    'failure',
                                    'Cấu hình lịch làm việc',
                                    'activity'
                                );

                                return $this->sendError('Chuỗi ngày nghỉ off_days chưa hợp lệ', 400);
                            }

                            // chứa ngày không có trong tháng
                            if ((int) $day > $daysInMonth || (int) $day < 1) {
                                $this->logging(
                                    'Cập nhật lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                                    $verifyApiToken['id'],
                                    $projectType,
                                    'failure',
                                    'Cấu hình lịch làm việc',
                                    'activity'
                                );

                                return $this->sendError('Chuỗi ngày nghỉ off_days chứa ngày không hợp lệ', 400);
                            }
                        }
                    } else {
                        // chứa toàn dấu phẩy và space
                        $this->logging(
                            'Cập nhật lịch làm việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                            $verifyApiToken['id'],
                            $projectType,
                            'failure',
                            'Cấu hình lịch làm việc',
                            'activity'
                        );

                        return $this->sendError('Chuỗi ngày nghỉ off_days chưa hợp lệ', 400);
                    }
                }
            }

            $updates = ['off_saturday', 'off_sunday'];

            foreach ($updates as $update) {
                $setting->{$update} = ((int) $request->get($update)) == 1 ? true : false;
            }

            $setting->off_days = $off_days;
            $setting->save();

            $this->create($month, $year);

            DB::commit();

            $this->logging(
                'Cập nhật lịch làm việc thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Cấu hình lịch làm việc',
                'activity'
            );

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            $this->logging(
                'Cập nhật lịch làm việc lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Cấu hình lịch làm việc',
                'activity'
            );

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

        $listing = ScheduleSetting::orderBy('year', 'asc')->orderBy('month', 'asc')->get();

        return $this->sendResponse($listing);
    }
}
