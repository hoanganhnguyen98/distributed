<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Task;
use App\Model\Employee;
use Carbon\Carbon;
use App\Http\Controllers\Distributed\TaskController as TaskController;
use App\Http\Controllers\Distributed\HistoryController;
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

        $create_id = Employee::where('employee_id', $verifyApiToken['id'])->first()->id;

        if ($task->captain_id != $create_id) {
            return $this->sendError('Bạn không có quyền gửi báo cáo kết quả', 403);
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
                $resize = array("width" => 300, "height" => 300, "crop" => "fill");
                $img_url = Cloudder::show($public_id, $resize);
            }

            Report::create([
                'title' => $request->get('title'),
                'content' => $request->get('content'),
                'task_id' => $task_id,
                'image' => $img_url,
                'status' => 'waiting',
                'type' => $projectType,
                'create_id' => $create_id
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
            return $this->sendError('Định danh báo cáo kết quả không hợp lệ', 400);
        }

        $task_id = $report->task_id;
        $task = Task::where('task_id', $task_id)->first();

        if (!$task) {
            return $this->sendError('Công việc xử lý của báo cáo không tồn tại', 400);
        }

        if ($task->status == 'done') {
            $report->status = 'accept';
            $report->save();

            return $this->sendError('Công việc xử lý đã được xác nhận từ trước', 400);
        }

        if ($task->status == 'doing') {
            $report->status = 'accept';
            $report->save();

            $action = "Chấp nhận báo cáo kết quả";
            $create_id = Employee::where('employee_id', $verifyApiToken['id'])->first()->id;
            (new HistoryController)->create($task_id, $action, $create_id);

            $task->status = 'done';
            $task->save();

            $action = "Sự cố đã được xử lý";
            (new HistoryController)->create($task_id, $action, $create_id);

            $task_id = $task->id;
            $doing_employees = Employee::where('current_id', $task_id)->get();
            $pending_employees = Employee::where('pending_ids', 'like', '%,'. $task_id . ',%')->get();

            foreach ($pending_employees as $employee) {
                // remove id from pending list
                $pending_ids = $employee->pending_ids;
                $pending_ids = str_replace($task_id . ',', '', $pending_ids);

                $employee->pending_ids = $pending_ids;
                $employee->save();

                $this->notification('pending', 'remove', $employee->id);
            }

            foreach ($doing_employees as $employee) {
                $employee->current_id = null;
                $employee->save();

                (new TaskController)->setCurrentTask($employee->id);
            }

            return $this->sendResponse();
        }

        return $this->sendError('Trạng thái của công việc xử lý không hợp lệ', 400);
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
            return $this->sendError('Định danh báo cáo kết quả không hợp lệ', 400);
        }

        $report->status = 'reject';
        $report->save();

        return $this->sendResponse();
    }
}
