<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Employee;
use App\Model\Task;
use Carbon\Carbon;

class EmployeeController extends BaseController
{
    public function active(Request $request)
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

        if (!$task_id) {
            return $this->sendError('Không có giá trị định danh sự cố', 400);
        }

        $task = Task::where('id', $task_id)->first();

        if (!$task) {
            return $this->sendError('Công việc xử lý không tồn tại', 404);
        }

        if ($task->status == 'done') {
            return $this->sendError('Công việc xử lý đã hoàn tất', 403);
        }

        $active_ids = $task->active_ids;
        $user_id = Employee::where('employee_id', $verifyApiToken['id'])->first()->id;

        if (strpos($active_ids, $user_id) !== false) {
            return $this->sendError('Công việc đã được khởi động', 403);
        }

        $active_ids .= $user_id . ',';
        $task->active_ids = $active_ids;
        $task->save();

        return $this->sendResponse();
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

        if (!$page || !$limit) {
            $employees = Employee::where('type',$type)->get();
        } else {
            $employees = Employee::where('type',$type)->offset(($page - 1) * $limit)->limit($limit)->get();

            $count = Employee::where('type',$type)->count();
            $total = ceil($count / $limit);

            $metadata = [
                'total' => (int) $total,
                'page' => (int) $page,
                'limit' => (int) $limit
            ];
        }

        $data = [
            'metadata' => $metadata,
            'employees' => $employees
        ];

        return $this->sendResponse($data);
    }

    public function detail(Request $request)
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

        $employee_id = $verifyApiToken['id'];

        if (!$employee_id) {
            return $this->sendError('Không có giá trị định danh nhân viên', 404);
        }

        $existedEmployee = Employee::where('employee_id', $employee_id)->first();

        if (!$existedEmployee) {
            $name = $verifyApiToken['name'];
            $role = $verifyApiToken['role'];

            $newEmployee = Employee::create([
                'employee_id' => $employee_id,
                'name' => $name,
                'role' => $role,
                'type' => $projectType,
                'current_id' => null,
                'pending_ids' => ',',
                'all_ids' => ','
            ]);

            $data = [
                'employee' => $newEmployee,
                'current_task' => null,
                'active_current_task' => false,
                'pending_tasks' => []
            ];

            return $this->sendResponse($data);
        }

        $current_id = $existedEmployee->current_id;
        $current_task = Task::where([['id', $current_id], ['status', '<>' ,'done']])->first();

        $active_task = false;
        if ($current_task) {
            $active_ids = $current_task->active_ids;

            if (strpos($active_ids, $current_id) !== false) {
                $active_task = true;
            }
        }

        $pending_ids = $existedEmployee->pending_ids;
        $pending_tasks = [];

        if (strlen($pending_ids) > 1) {
            $pending_ids_array = array_slice(explode(',', $pending_ids), 1, -1);

            foreach ($pending_ids_array as $id) {
                $task = Task::where([['id', $id], ['status', '<>' ,'done']])->first();

                if ($task) {
                    $pending_tasks[] = $task;
                }
            }
        }

        $data = [
            'employee' => $existedEmployee,
            'current_task' => $current_task,
            'active_current_task' => $active_task,
            'pending_tasks' => $pending_tasks
        ];

        return $this->sendResponse($data);
    }
}
