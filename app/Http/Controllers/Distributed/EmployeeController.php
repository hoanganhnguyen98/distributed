<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Employee;
use App\Model\Task;
use Carbon\Carbon;

class EmployeeController extends BaseController
{
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
            return $this->sendError('Không có giá trị định danh nhân viên', 400);
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
                'pending_ids' => ','
            ]);

            $data = [
                'employee' => $newEmployee,
                'current_task' => [],
                'pending_tasks' => []
            ];

            return $this->sendResponse($data);
        }

        $current_id = $existedEmployee->current_id;
        $current_task = Task::where([['id', $current_id], ['status', 'doing']])->first();

        $pending_ids = $existedEmployee->pending_ids;
        $pending_tasks = [];

        if (strlen($pending_ids) > 1) {
            $pending_ids_array = array_slice(explode(',', $pending_ids), 1, -1);

            foreach ($pending_ids_array as $id) {
                $task = Task::where([['id', $id], ['status', 'doing']])->first();

                if ($task) {
                    $pending_tasks[] = $task;
                }
            }
        }

        $data = [
            'employee' => $existedEmployee,
            'current_task' => $current_task,
            'pending_tasks' => $pending_tasks
        ];

        return $this->sendResponse($data);
    }
}
