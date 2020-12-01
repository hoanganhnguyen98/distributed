<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Task;
use App\Model\Support;
use App\Model\Employee;
use App\Model\History;
use Carbon\Carbon;
use App\Http\Controllers\Distributed\TaskController as TaskController;

class ExternalController extends BaseController
{
    public function getUserTasks(Request $request)
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
                'current_task' => null,
                'pending_tasks' => [],
                'done_tasks' => []
            ];

            return $this->sendResponse($data);
        }

        $current_id = $existedEmployee->current_id;
        $current_task = Task::where([['id', $current_id], ['status', '<>' ,'done']])->first();

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

        $all_ids = $existedEmployee->all_ids;
        $done_tasks = [];

        if (strlen($all_ids) > 1) {
            $done_ids_array = array_slice(explode(',', $all_ids), 1, -1);

            foreach ($done_ids_array as $id) {
                $task = Task::where([['id', $id], ['status' ,'done']])->first();

                if ($task) {
                    $done_tasks[] = $task;
                }
            }
        }

        $data = [
            'current_task' => $current_task,
            'pending_tasks' => $pending_tasks,
            'done_tasks' => $done_tasks
        ];

        return $this->sendResponse($data);
    }

    public function getTaskByIncidentId(Request $request)
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

        $incident_id = $request->get('id');

        if (!$incident_id) {
            return $this->sendError('Không có giá trị định danh sự cố', 400);
        }

        $task = Task::where([['incident_id', $incident_id], ['type', $projectType]])->first();

        if (!$task) {
            return $this->sendError('Công việc xử lý không tồn tại', 400);
        }

        $id = $task->id;

        $histories = History::where('task_id', $id)->orderBy('created_at', 'asc')->get();

        $doing_employees = Employee::where('current_id', $id)->get();
        $pending_employees = Employee::where('pending_ids', 'like', '%,'. $id . ',%')->get();

        $data = [
            'task' => $task,
            'histories' => $histories,
            'doing_employees' => $doing_employees,
            'pending_employees' => $pending_employees
        ];

        return $this->sendResponse($data);
    }

    public function reportListing()
    {
        $reports = $this->reportCounting();
        $supports = $this->supportCounting();
        $tasks = $this->taskCounting();
        $employees = $this->employeeCounting();

        $data = [
            'result_reports_total' => $reports,
            'support_requests_total' => $supports,
            'created_tasks_total' => $tasks,
            'joined_employee' => $employees
        ];

        return $this->sendResponse($data);
    }

    public function reportCounting()
    {
        $reports = Report::all()->count();
        $accepts = Report::where('status', 'accept')->get()->count();
        $rejects = Report::where('status', 'reject')->get()->count();
        $waitings = $reports - $accepts - $rejects;

        return [
            'label' => 'Báo cáo kết quả xử lý sự cố',
            'sent_total' => $reports,
            'accepted_total' => $accepts,
            'rejected_total' => $rejects,
            'waiting_total' => $waitings
        ];
    }

    public function supportCounting()
    {
        $supports = Support::all()->count();
        $accepts = Support::where('status', 'accept')->get()->count();
        $rejects = Support::where('status', 'reject')->get()->count();
        $waitings = $supports - $accepts - $rejects;

        return [
            'label' => 'Yêu cầu hỗ trợ xử lý sự cố',
            'sent_total' => $supports,
            'accepted_total' => $accepts,
            'rejected_total' => $rejects,
            'waiting_total' => $waitings
        ];
    }

    public function taskCounting()
    {
        $tasks = Task::all()->count();
        $doing = Task::where('status', 'doing')->get()->count();
        $done = Task::where('status', 'done')->get()->count();

        return [
            'label' => 'Công việc xử lý sự cố',
            'created_total' => $tasks,
            'doing_total' => $doing,
            'done_total' => $done,
        ];
    }

    public function employeeCounting()
    {
        $employees = Employee::all()->count();

        return [
            'label' => 'Nhân viên tham gia xử lý sự cố',
            'joined_total' => $employees
        ];
    }
}
