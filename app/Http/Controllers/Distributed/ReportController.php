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
            return $this->sendError('Không có giá trị định danh báo cáo kết quả', 400);
        }

        $task = Task::where([['id', $task_id], ['status', '<>', 'done']])->first();

        if (!$task) {
            return $this->sendError('Định danh báo cáo kết quả không hợp lệ', 404);
        }

        $employee_id = $verifyApiToken['id'];

        $employee_ids = $task->employee_ids;

        if (strpos($employee_ids, ',') > 0) {
            $valid_ids = explode(',', $employee_ids);

            if (!in_array($employee_id, $valid_ids)) {
                return $this->sendError('Nhân viên không trong phạm vi xử lý của công việc này', 403);
            }
        } else {
            if ($employee_id !== $employee_ids) {
                return $this->sendError('Nhân viên không trong phạm vi xử lý của công việc này', 403);
            }
        }

        $rules = [
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'image' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('Thông tin gửi đi không hợp lệ', 400);
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')){
                // create path to store in cloud
                $public_id = "ninja_restaurant/distributed/reports/" . ($create_id . '_' . Carbon::now()->timestamp);
                // upload to cloud
                Cloudder::upload($request->file('image'), $public_id);
                // get url of image
                $resize = array("width" => null, "height" => null, "crop" => null);
                $img_url = Cloudder::show($public_id, $resize);
            }

            Report::create([
                'title' => $request->get('title'),
                'content' => $request->get('content'),
                'task_id' => $task_id,
                'image' => $img_url,
                'status' => 'waiting',
                'type' => $projectType,
                'create_id' => $employee_id
            ]);

            DB::commit();

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

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

        $type_id = $projectType;

        $page = $request->get('page');
        $limit = $request->get('limit');
        $metadata = [];

        if (!$page || !$limit) {
            $list = Report::where('type',$type_id)->get();
        } else {
            $list = Report::where('type',$type_id)->offset(($page - 1) * $limit)->limit($limit)->get();

            $count = Report::where('type',$type_id)->count();
            $total = ceil($count / $limit);

            $metadata = [
                'total' => (int) $total,
                'page' => (int) $page,
                'limit' => (int) $limit
            ];
        }

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
            return $this->sendError('Không có giá trị định danh báo cáo kết quả', 400);
        }

        $report = Report::where([['id', $id], ['status', 'waiting'], ['type', $type]])->first();

        if (!$report) {
            return $this->sendError('Không tìm được báo cáo nào hợp lệ', 404);
        }

        $task_id = $report->task_id;
        $task = Task::where('task_id', $task_id)->first();

        if (!$task) {
            return $this->sendError('Công việc xử lý của báo cáo không tồn tại', 404);
        }

        if ($task->status == 'done') {
            $report->status = 'accept';
            $report->save();

            return $this->sendError('Công việc xử lý đã được xác nhận từ trước', 400);
        }

        try {
            DB::beginTransaction();

            if ($task->status == 'doing') {
                $report->status = 'accept';
                $report->save();

                $task->status = 'done';
                $task->save();

                $task_id = $task->id;
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

                    $this->notification('pending', 'remove', $employee->id);
                }

                foreach ($doing_employees as $employee) {
                    $employee->current_id = null;

                    $all_ids = $employee->all_ids;

                    if ($all_ids) {
                        $new_all_ids = $all_ids . ',' . $task_id;
                    } else {
                        $new_all_ids = $task_id;
                    }

                    $employee->all_ids = $new_all_ids;
                    $employee->save();

                    (new TaskController)->setCurrentTask($apiToken, $projectType, $employee->employee_id);
                }
            }

            DB::commit();

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

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
            return $this->sendError('Không có giá trị định danh báo cáo kết quả', 400);
        }

        $report = Report::where([['id', $id], ['status', 'waiting'], ['type', $type]])->first();

        if (!$report) {
            return $this->sendError('Không tìm được báo cáo nào hợp lệ', 404);
        }

        $report->status = 'reject';
        $report->save();

        return $this->sendResponse();
    }
}
