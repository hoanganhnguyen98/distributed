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
    }

    public function detail(Request $request)
    {
        $employee_id = $request->get('id');

        if (!$employee_id) {
            return $this->sendError('Không có giá trị định danh nhân viên', 400);
        }

        $employee = Employee::where('employee_id', $employee_id)->first();

        if (!$employee) {
            return $this->sendError('Nhân viên chưa tham gia vào công việc nào', 400);
        }

        $current_id = $employee->current_id;
        $current_task = Task::where([['id', $current_id], ['status', 'doing']])->first();

        $pending_ids = $employee->pending_ids;
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

        $data[] = [
            'employee' => $employee,
            'current_task' => $current_task,
            'pending_tasks' => $pending_tasks
        ];

        return $this->sendResponse($data);
    }
}
