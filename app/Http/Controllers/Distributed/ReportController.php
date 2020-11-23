<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Task;
use Carbon\Carbon;
use App\Http\Controllers\Distributed\TaskController as TaskController;

class ReportController extends BaseController
{
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
        $id = $request->get('id');

        if(!$id) {
            return $this->sendError('Không có giá trị định danh báo cáo kết quả', 400);
        }

        $report = Report::where([['id', $id], ['status', 'waiting']])->first();

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

            $task->status = 'done';
            $task->save();

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
        $id = $request->get('id');

        if(!$id) {
            return $this->sendError('Không có giá trị định danh báo cáo kết quả', 400);
        }

        $report = Report::where([['id', $id], ['status', 'waiting']])->first();

        if (!$report) {
            return $this->sendError('Định danh báo cáo kết quả không hợp lệ', 400);
        }

        $report->status = 'reject';
        $report->save();

        return $this->sendResponse();
    }
}
