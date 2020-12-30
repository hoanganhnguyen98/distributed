<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Task;
use App\Model\Employee;
use Carbon\Carbon;
use App\Http\Controllers\Distributed\TaskController as TaskController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Cloudder;
use App\Http\Controllers\Distributed\ZoneAreaController as ZoneAreaController;

class ReportController extends BaseController
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
                return $this->sendError($verifyApiToken['message'], $statusCode);
            }
        }

        $task_id = $request->get('id');

        if(!$task_id) {
            $this->logging(
                'Tạo báo cáo lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Không có giá trị định danh công việc xử lý sự cố', 400);
        }

        $task = Task::where([['id', $task_id], ['status', '<>', 'done']])->first();

        if (!$task) {
            $this->logging(
                'Tạo báo cáo lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Định danh công việc xử lý không hợp lệ', 404);
        }

        $employee_id = $verifyApiToken['id'];

        $employee_ids = $task->employee_ids;

        if (strpos($employee_ids, ',') > 0) {
            $valid_ids = explode(',', $employee_ids);

            if (!in_array($employee_id, $valid_ids)) {
                $this->logging(
                    'Tạo báo cáo lỗi do nhân viên không thuộc phạm vi công việc báo cáo',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Báo cáo kết quả xử lý',
                    'report'
                );

                return $this->sendError('Nhân viên không trong phạm vi xử lý của công việc này', 403);
            }
        } else {
            if ((int) $employee_id !== (int) $employee_ids) {
                $this->logging(
                    'Tạo báo cáo lỗi do nhân viên không thuộc phạm vi công việc báo cáo',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Báo cáo kết quả xử lý',
                    'report'
                );

                return $this->sendError('Nhân viên không trong phạm vi xử lý của công việc này', 403);
            }
        }

        $existedReport = Report::where([['create_id', $employee_id], ['status', 'waiting']])->first();

        if ($existedReport) {
            $this->logging(
                'Tạo báo cáo lỗi do đang có một báo cáo chờ xử lý',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Đã có một báo cáo được gửi đi, hãy chờ được xử lý', 403);
        }

        if ($request->hasFile('file')){
            $size = $request->file('file')->getSize();

            if ($size > 5800000) {
                $this->logging(
                    'Tạo báo cáo lỗi do tệp đính kèm quá lớn',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Báo cáo kết quả xử lý',
                    'report'
                );

                return $this->sendError('Kích thước tệp đính kèm có dung lượng không quá 5800000', 400);
            }
        } else {
            $this->logging(
                'Tạo báo cáo lỗi do không có tệp đính kèm',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Không có tệp đính kèm', 400);
        }

        $rules = [
            'title' => ['required', 'string'],
            'content' => ['required', 'string']
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $this->logging(
                'Tạo báo cáo lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Thông tin gửi đi không hợp lệ', 400);
        }

        try {
            DB::beginTransaction();

            $img_url = null;
            if ($request->hasFile('file')){
                $type = $request->file('file')->getMimeType();

                $public_id = "ninja_restaurant/distributed/reports/" . ($employee_id . '_' . Carbon::now()->timestamp);

                if (in_array('video', explode('/', $type))) {
                    Cloudder::uploadVideo($request->file('file'), $public_id);
                } else {
                    Cloudder::upload($request->file('file'), $public_id);
                }

                $resize = array("width" => null, "height" => null, "crop" => null);
                $img_url = Cloudder::getResult($public_id, $resize)['url'];
            }

            $newReport = Report::create([
                'title' => $request->get('title'),
                'content' => $request->get('content'),
                'task_id' => $task_id,
                'image' => $img_url,
                'status' => 'waiting',
                'type' => $projectType,
                'create_id' => $employee_id
            ]);

            $this->logging(
                'Tạo báo cáo thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Báo cáo kết quả xử lý',
                'report',
                $newReport->id
            );

            $this->notification(
                $apiToken,
                $projectType,
                $employee_id,
                [$employee_id],
                $newReport->id,
                "http://14.248.5.197:8010/handle-problem",
                "Báo cáo kết quả mới",
                2
            );

            DB::commit();

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            $this->logging(
                'Tạo báo cáo lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Đã có lỗi xảy ra khi tạo báo cáo', 500);
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
                return $this->sendError($verifyApiToken['message'], $statusCode);
            }
        }

        $type = $projectType;

        $page = $request->get('page');
        $limit = $request->get('limit');
        $metadata = [];

        // if (!$page || !$limit) {

            if ($type == 'ALL_PROJECT') {
                $list = Report::all();
            } else {
                $list = Report::where('type',$type)->get();
            }
        // } else {
        //     if ($type == 'ALL_PROJECT') {
        //         $list = Report::all();
        //     } else {
        //         $list = Report::where('type',$type)->get();
        //     }

        //     $list = Report::where('type',$type)->offset(($page - 1) * $limit)->limit($limit)->get();

        //     $count = Report::where('type',$type)->count();
        //     $total = ceil($count / $limit);

        //     $metadata = [
        //         'total' => (int) $total,
        //         'page' => (int) $page,
        //         'limit' => (int) $limit
        //     ];
        // }

        $data = [
            'metadata' => $metadata,
            'list' => $list
        ];

        return $this->sendResponse($data);
    }

    public function accept(Request $request)
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
        $type = $projectType;

        if(!$id) {
            $this->logging(
                'Chấp nhận báo cáo lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Không có giá trị định danh báo cáo kết quả', 400);
        }

        $report = Report::where([['id', $id], ['status', 'waiting'], ['type', $type]])->first();

        if (!$report) {
            $this->logging(
                'Chấp nhận báo cáo lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Không tìm được báo cáo nào hợp lệ', 404);
        }

        $task_id = $report->task_id;
        $task = Task::where('id', $task_id)->first();

        if (!$task) {
            $this->logging(
                'Chấp nhận báo cáo lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Công việc xử lý của báo cáo không tồn tại', 404);
        }

        if ($task->status == 'done') {
            $report->status = 'accept';
            $report->save();

            $this->logging(
                'Chấp nhận báo cáo lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Công việc xử lý đã được xác nhận từ trước', 400);
        }

        try {
            DB::beginTransaction();

            if ($task->status == 'doing') {
                $report->status = 'accept';
                $report->save();

                $this->notification(
                    $apiToken,
                    $projectType,
                    $verifyApiToken['id'],
                    [$report->create_id],
                    $id,
                    "http://14.248.5.197:8010/handle-problem",
                    "Báo cáo được chấp nhận",
                    3
                );

                $task->status = 'done';
                $task->save();

                $this->finishHandler($task, $apiToken, $projectType);

                $doing_employees = Employee::where('current_id', $task_id)->get();
                $pending_employees = Employee::where('pending_ids', 'like', '%'. $task_id . '%')->get();

                foreach ($pending_employees as $employee) {
                    $pending_ids = $employee->pending_ids;

                    if (strpos($pending_ids, ',') > 0) {
                        $pending_array = explode(',', $pending_ids);

                        if (in_array($task_id, $pending_array)) {
                            foreach ($pending_array as $key => $value) {
                                if ($value == $task) {
                                    unset($pending_array[$key]);

                                    break;
                                }
                            }

                            if (!empty($pending_array)) {
                                $new_pending_ids = '';

                                foreach ($pending_array as $id) {
                                    $new_pending_ids .= $id . ',';
                                }

                                $employee->pending_ids = rtrim($new_pending_ids, ", ");
                            } else {
                                $employee->pending_ids = null;
                            }

                            $employee->save();
                        }
                    } else {
                        if ($pending_ids === $task_id) {
                            $employee->pending_ids = null;
                            $employee->save();
                        }
                    }
                }

                foreach ($doing_employees as $employee) {
                    $employee->current_id = null;

                    $this->createUserMeta(
                        $apiToken,
                        $projectType,
                        $verifyApiToken['id'],
                        $employee->employee_id,
                        "Yêu cầu nhân viên xử lý công việc",
                        "DONE",
                        $task_id,
                        $projectType
                    );

                    $all_ids = $employee->all_ids;

                    if ($all_ids) {
                        $new_all_ids = $all_ids . ',' . $task_id;
                    } else {
                        $new_all_ids = $task_id;
                    }

                    $employee->all_ids = $new_all_ids;
                    $employee->save();

                    (new TaskController)->setCurrentTask($apiToken, $projectType, $employee->employee_id, $verifyApiToken['id']);
                }
            }

            DB::commit();

            $this->logging(
                'Chấp nhận báo cáo thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Báo cáo kết quả xử lý',
                'report',
                $id
            );

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            $this->logging(
                'Chấp nhận báo cáo lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Đã có lỗi xảy ra khi chấp nhận báo cáo', 500);
        }
    }

    public function reject(Request $request)
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
        $type = $projectType;

        if(!$id) {
            $this->logging(
                'Từ chối báo cáo lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Không có giá trị định danh báo cáo kết quả', 400);
        }

        $report = Report::where([['id', $id], ['status', 'waiting'], ['type', $type]])->first();

        if (!$report) {
            $this->logging(
                'Từ chối báo cáo lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Không tìm được báo cáo nào hợp lệ', 404);
        }

        try {
            DB::beginTransaction();

            $report->status = 'reject';
            $report->save();

            $this->notification(
                $apiToken,
                $projectType,
                $verifyApiToken['id'],
                $report->create_id,
                $id,
                "http://14.248.5.197:8010/handle-problem",
                "Báo cáo bị từ chối",
                3
            );

            DB::commit();

            $this->logging(
                'Từ chối báo cáo thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Báo cáo kết quả xử lý',
                'report',
                $id
            );

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            $this->logging(
                'Từ chối báo cáo lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Báo cáo kết quả xử lý',
                'report'
            );

            return $this->sendError('Đã có lỗi xảy ra khi chấp nhận báo cáo', 500);
        }
    }

    public function finishHandler($task, $apiToken, $projectType)
    {
        $incident_id = $task->incident_id;
        $all_tasks = Task::where([['incident_id', $incident_id], ['status', '<>' , 'done']])->get()->count();

        if (!$all_tasks) {
            (new TaskController)->updateIncidentStatus($incident_id, $apiToken, $projectType, 2);
            (new ZoneAreaController)->updateTimes($apiToken, $projectType, $incident_id);
        }
    }
}
