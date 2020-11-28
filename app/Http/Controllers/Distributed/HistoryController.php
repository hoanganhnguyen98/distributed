<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Task;
use App\Model\Employee;
use App\Model\History;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request as ApiRequest;

class HistoryController extends BaseController
{
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

        $task_id = $request->get('id');

        if (!$task_id) {
            return $this->sendError('Không có định danh công việc xử lý sự cố', 400);
        }

        $task = Task::where('id', $task_id)->first();

        if (!$task) {
            return $this->sendError('Công việc xử lý sự cố không tồn tại', 404);
        }

        if ($task->type !== $projectType) {
            return $this->sendError('Tài khoản không thuộc phạm vi quản lý sự cố', 403);
        }

        $histories = History::where('task_id', $task_id)->orderBy('created_at', 'asc')->get();

        return $this->sendResponse($histories);
    }

    public function create($task_id, $action, $create_id, $support_ids = null)
    {
        $task = Task::where([['id', $task_id], ['status', 'doing']])->first();

        if ($task) {
            $doing_ids = $this->getDoingIds($task_id);
            $pending_ids = $this->getPendingIds($task_id);

            History::create([
                'task_id' => $task_id,
                'action' => $action,
                'doing_ids' => $doing_ids,
                'pending_ids' => $pending_ids,
                'support_ids' => $support_ids,
                'create_id' => $create_id
            ]);
        }
    }

    public function getDoingIds($task_id)
    {
        $doing_ids = ',';

        $doing_employees = Employee::where('current_id', $task_id)->get();
        foreach ($doing_employees as $key => $employee) {
            $doing_ids .= $employee->id . ',';
        }

        return $doing_ids;
    }

    public function getPendingIds($task_id)
    {
        $pending_ids = ',';

        $pending_employees = Employee::where('pending_ids', 'like', '%,'. $task_id . ',%')->get();
        foreach ($pending_employees as $key => $employee) {
            $pending_ids .= $employee->id . ',';
        }

        return $pending_ids;
    }
}
