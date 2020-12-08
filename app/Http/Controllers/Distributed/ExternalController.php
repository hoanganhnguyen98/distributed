<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Task;
use App\Model\TaskType;
use App\Model\Employee;
use Carbon\Carbon;
use App\Http\Controllers\Distributed\TaskController as TaskController;

class ExternalController extends BaseController
{
    public $type;

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
                'current_id' => null,
                'pending_ids' => null,
                'all_ids' => null,
                'type' => $projectType
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

        $main_task = [];
        if ($current_task) {
            $result['task'] = $current_task;

            $task_type_id = $current_task->task_type_id;
            $task_type = TaskType::where('id', $task_type_id)->first();
            $result['task_type'] = $task_type;

            $main_task = $result;
        }

        $pending_ids = $existedEmployee->pending_ids;
        $pending_tasks = [];

        if ($pending_ids) {
            if (strpos($pending_ids, ',') > 0) {
                foreach (explode(',', $pending_ids) as $id) {
                    $task = Task::where([['id', $id], ['status', '<>' ,'done']])->first();

                    if ($task) {
                        $result['task'] = $task;

                        $task_type_id = $task->task_type_id;
                        $task_type = TaskType::where('id', $task_type_id)->first();
                        $result['task_type'] = $task_type;

                        $pending_tasks[] = $result;
                    }
                }
            } else {
                $task = Task::where([['id', $pending_ids], ['status', '<>' ,'done']])->first();

                if ($task) {
                    $result['task'] = $task;

                    $task_type_id = $task->task_type_id;
                    $task_type = TaskType::where('id', $task_type_id)->first();
                    $result['task_type'] = $task_type;

                    $pending_tasks[] = $result;
                }
            }
        }

        $all_ids = $existedEmployee->all_ids;
        $done_tasks = [];

        if ($all_ids) {
            if (strpos($all_ids, ',') > 0) {
                foreach (explode(',', $all_ids) as $id) {
                    $task = Task::where([['id', $id], ['status', '<>' ,'done']])->first();

                    if ($task) {
                        $result['task'] = $task;

                        $task_type_id = $task->task_type_id;
                        $task_type = TaskType::where('id', $task_type_id)->first();
                        $result['task_type'] = $task_type;

                        $done_tasks[] = $result;
                    }
                }
            } else {
                $task = Task::where([['id', $all_ids], ['status', '<>' ,'done']])->first();

                if ($task) {
                    $result['task'] = $task;

                    $task_type_id = $task->task_type_id;
                    $task_type = TaskType::where('id', $task_type_id)->first();
                    $result['task_type'] = $task_type;

                    $done_tasks[] = $result;
                }
            }
        }

        $data = [
            'current_task' => $main_task,
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

        $doing_employees = Employee::where('current_id', $id)->get();
        $pending_employees = Employee::where('pending_ids', 'like', '%,'. $id . ',%')->get();

        $data = [
            'task' => $task,
            'doing_employees' => $doing_employees,
            'pending_employees' => $pending_employees
        ];

        return $this->sendResponse($data);
    }

    public function reportListing(Request $request)
    {
        $list = ['LUOI_DIEN', 'CHAY_RUNG', 'DE_DIEU', 'CAY_TRONG'];
        $type = $request->get('type');

        if ($type) {
            if (!in_array($type, $list)) {
                return $this->sendError('Loại dự án chưa chính xác', 400);
            }
        }

        $this->type = $type;

        $reports = $this->reportCounting();
        $tasks = $this->taskCounting();
        $employees = $this->employeeCounting();
        $task_type = $this->taskTypeCounting();

        $data = [
            'created_tasks_total' => $tasks,
            'task_types_total' => $task_type,
            'result_reports_total' => $reports,
            'joined_employees_total' => $employees
        ];

        return $this->sendResponse($data);
    }

    public function reportCounting()
    {
        if ($this->type) {
            $reports = Report::where('type', $this->type)->get()->count();
            $accepts = Report::where([['status', 'accept'], ['type', $this->type]])->get()->count();
            $rejects = Report::where([['status', 'reject'], ['type', $this->type]])->get()->count();
            $waitings = $reports - $accepts - $rejects;
        } else {
            $reports = Report::all()->count();
            $accepts = Report::where('status', 'accept')->get()->count();
            $rejects = Report::where('status', 'reject')->get()->count();
            $waitings = $reports - $accepts - $rejects;
        }

        return [
            'label' => 'Báo cáo kết quả xử lý sự cố',
            'sent_total' => $reports,
            'accepted_total' => $accepts,
            'rejected_total' => $rejects,
            'waiting_total' => $waitings
        ];
    }

    public function taskCounting()
    {
        if ($this->type) {
            $all_tasks = Task::all();

            $tasks = [];
            foreach ($all_tasks as $key => $task) {
                $task_type_id = $task->task_type_id;

                $task_type = TaskType::where('id', $task_type_id)->first();

                if ($task_type) {
                    if ($task_type->project_type === $this->type) {
                        $tasks[] = $task;
                    }
                }
            }

            $total = count($tasks);
            $doing = 0;
            $done = 0;
            $pending = 0;

            if ($total) {
                foreach ($tasks as $key => $task) {
                    $status = $task->status;

                    if ($status === 'pending') {
                        $pending ++;
                    } elseif ($status === 'doing') {
                        $doing ++;
                    } elseif ($status === 'done') {
                        $done ++;
                    }
                }
            }
        } else {
            $total = Task::all()->count();
            $doing = Task::where('status', 'doing')->get()->count();
            $done = Task::where('status', 'done')->get()->count();
            $pending = $total - $doing - $done;
        }

        return [
            'label' => 'Công việc xử lý sự cố',
            'created_total' => $total,
            'doing_total' => $doing,
            'done_total' => $done,
            'pending_total' => $pending
        ];
    }

    public function employeeCounting()
    {
        if ($this->type) {
            $employees = Employee::where('type', $this->type)->get()->count();
        } else {
            $employees = Employee::all()->count();
        }

        return [
            'label' => 'Nhân viên tham gia xử lý sự cố',
            'joined_total' => $employees
        ];
    }

    public function taskTypeCounting()
    {
        if ($this->type) {
            $task_type = TaskType::where('project_type', $this->type)->get()->count();
        } else {
            $task_type = TaskType::all()->count();
        }

        return [
            'label' => 'Loại công việc xử lý sự cố',
            'created_total' => $task_type
        ];
    }
}
