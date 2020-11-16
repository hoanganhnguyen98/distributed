<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Task;
use App\Model\Employee;
use Carbon\Carbon;

class TaskController extends BaseController
{
    protected $incident;

    protected $employees;

    public function __construct()
    {
        $this->incident = [];
        $this->employees = [];
    }

    public function listing(Request $request)
    {
        $type_id = $request->get('id');

        if(!$type_id) {
            return $this->sendError('Không có giá trị định danh nhóm sự cố', 400);
        }

        $page = $request->get('page');
        $limit = $request->get('limit');
        $metadata = [];

        if (!$page || !$limit) {
            $tasks = Task::where('type',$type_id)->get();
        } else {
            $tasks = Task::where('type',$type_id)->offset(($page - 1) * $limit)->limit($limit)->get();

            $count = Task::where('type',$type_id)->count();
            $total = ceil($count / $limit);

            $metadata = [
                'total' => (int) $total,
                'page' => (int) $page,
                'limit' => (int) $limit
            ];
        }

        $data[] = [
            'metadata' => $metadata,
            'tasks' => $tasks
        ];

        return $this->sendResponse($data);
    }

    public function detail(Request $request)
    {
        $id = $request->get('id');

        if (!$id) {
            return $this->sendError('Không có giá trị định danh sự cố', 400);
        }

        $task = Task::where('id', $id)->first();

        if (!$task) {
            return $this->sendError('Công việc xử lý không tồn tại', 400);
        }

        $doing_employees = Employee::where('current_id', $id)->get();
        $pending_employees = Employee::where('pending_ids', 'like', '%,'. $id . ',%')->get();

        $data = [
            'task' => $task,
            'doing_employees' => $doing_employees,
            'pending_employees' => $pending_employees
        ];

        return $this->sendResponse($data);
    }

    public function handler(Request $request)
    {
        $incident_id = $request->get('id');

        if (!$incident_id) {
            return $this->sendError('Không có giá trị định danh sự cố', 400);
        }

        // checking to get incident information
        $this->incident = $this->incidentChecking($incident_id);

        // get employee to handler new task
        $captain_id = $this->employeeGetting();

        // create new task
        $task_id = $this->createTask($captain_id);

        foreach ($this->employees as $employee) {
            $employee_id = $employee['employee_id'];
            $name = $employee['name'];

            $this->setNewTask($task_id, $employee_id, $name, $this->incident['priority']);
        }

        return $this->sendResponse(['task_id' => $task_id]);
    }

    public function createTask($captain_id)
    {
        $new_task = Task::create([
            'incident_id' => $this->incident['incident_id'],
            'status' => 'doing',
            'name' => $this->incident['name'],
            'type' => $this->incident['type'],
            'level' => $this->incident['level'],
            'priority' => $this->incident['priority'],
            'captain_id' => $captain_id,
        ]);

        return $new_task->id;
    }

    public function employeeGetting()
    {
        $this->employees = [
            [
                'employee_id' => 999,
                'name' => 'Nguyen Van A'
            ],
            [
                'employee_id' => 888,
                'name' => 'Tran Van B'
            ],
            [
                'employee_id' => 777,
                'name' => 'Le Thi C'
            ],
        ];

        return 999;
    }

    // khi co mot task moi
    public function setNewTask($task_id, $employee_id, $name, $priority)
    {
        $existed_employee = Employee::where('employee_id', $employee_id)->first();

        if ($existed_employee) {
            $current_id = $existed_employee->current_id;
            $pending_ids = $existed_employee->pending_ids;

            if ($current_id) {
                $current_task = Task::where([['id', $current_id], ['status', 'doing']])->first();

                if ($current_task) {
                    $current_priority = $current_task->priority;

                    if ($current_priority >= $priority) {
                        $pending_ids .= $task_id . ',';
                    } else {
                        $new_current_id = $task_id;
                        $existed_employee->current_id = $new_current_id;

                        $pending_ids .= $current_id . ',';
                    }

                    $existed_employee->pending_ids = $pending_ids;
                    $existed_employee->save();

                    $this->notification('pending', 'add', $employee_id);
                } else {
                    $existed_employee->current_id = null;

                    $pending_ids .= $task_id . ',';
                    $existed_employee->pending_ids = $pending_ids;
                    $existed_employee->save();

                    $this->setCurrentTask($employee_id);
                }
            } else {
                $pending_ids .= $task_id . ',';
                $existed_employee->pending_ids = $pending_ids;
                $existed_employee->save();

                $this->setCurrentTask($employee_id);
            }
        } else {
            Employee::create([
                'employee_id' => $employee_id,
                'name' => $name,
                'current_id' => $task_id,
                'pending_ids' => ','
            ]);

            $this->notification('current', 'create', $employee_id);
        }
    }

    // khi nhan vien da hoan thanh mot task (task hien tai trong)
    public function setCurrentTask($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->first();

        $pending_ids = $employee->pending_ids;

        // if pending task not null
        if (strlen($pending_ids) > 1) {
            $pending_ids_array = array_slice(explode(',', $pending_ids), 1, -1);
            // array_slice($array, 1, -1);

            $list = [];
            foreach ($pending_ids_array as $id) {
                $task = Task::where([['id', $id], ['status', 'doing']])->first();

                if ($task) {
                    $list[] = [
                        'id' => $id,
                        'priority' => $task->priority,
                        'created_at' => $task->created_at
                    ];
                } else {
                    // remove id from pending list
                    $pending_ids = str_replace($id . ',', '', $pending_ids);

                    $this->notification('pending', 'remove', $employee_id);
                }
            }

            if (count($list)) {
                array_multisort(array_column($list, "priority"), SORT_ASC, array_column($list, "created_at"), SORT_DESC, $list);

                $current_task = end($task);
                $new_current_id = end($task)['id'];

                $employee->current_id = $new_current_id;
                $employee->save();

                $this->notification('current', 'push', $employee_id);

                $pending_ids = str_replace($new_current_id . ',', '', $pending_ids);

                $this->notification('pending', 'remove', $employee_id);
            }

            $employee->pending_ids = $pending_ids;
            $employee->save();
        }
    }

    public function incidentChecking($incident_id)
    {
        return [
            'incident_id' => $incident_id,
            'name' => 'Sự cố lưới điện ZZZ ' . rand(1000, 9999),
            'type' => '000000',
            'level' => 'Sự cố cấp I',
            'priority' => rand(1,3)
        ];
    }
}
