<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Task;
use App\Model\TaskType;
use App\Model\Employee;
use App\Model\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Distributed\HistoryController;

class TaskTypeController extends BaseController
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

        $create_id = Employee::where('employee_id', $verifyApiToken['id'])->first()->id;

        try {
            DB::beginTransaction();

            $rules = [
                'name' => ['required', 'string'],
                'description' => ['required', 'string'],
                'employee_number' => ['required', 'numeric'],
                'prioritize' => ['required', 'numeric', 'min:0', 'max:1']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $this->logging(
                    'Tạo mới loại công việc xử lý sự cố lỗi do dữ liệu đầu vào chưa hợp lệ',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Loại công việc xử lý sự cố',
                    'add'
                );

                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 400);
            }

            $prioritize = ((int) $request->get('prioritize')) == 1 ? true : false;

            $newTaskType = TaskType::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'employee_number' => (int) $request->get('employee_number'),
                'project_type' => $projectType,
                'prioritize' => $prioritize,
                'create_id' => $create_id
            ]);

            $this->logging(
                'Tạo mới loại công việc xử lý sự cố thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Loại công việc xử lý sự cố',
                'add',
                $newTaskType->id
            );

            DB::commit();

            return $this->sendResponse($newTaskType);
        } catch (Exception $e) {
            DB::rollBack();

            $this->logging(
                'Tạo mới loại công việc xử lý sự cố lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Loại công việc xử lý sự cố',
                'add'
            );

            return $this->sendError('Có lỗi khi tạo loại công việc mới', 500);
        }
    }

    public function update(Request $request)
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

        try {
            DB::beginTransaction();

            $rules = [
                'id' => ['required'],
                'name' => ['required', 'string'],
                'description' => ['required', 'string'],
                'employee_number' => ['required', 'numeric'],
                'prioritize' => ['required', 'numeric', 'min:0', 'max:1']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $this->logging(
                    'Cập nhật loại công việc xử lý sự cố lỗi do dữ liệu đầu vào chưa hợp lệ',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Loại công việc xử lý sự cố',
                    'add'
                );

                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 400);
            }

            $prioritize = ((int) $request->get('prioritize')) == 1 ? true : false;

            $taskType = TaskType::where('id', $request->get('id'))->first();

            if (!$taskType) {
                $this->logging(
                    'Cập nhật loại công việc xử lý sự cố lỗi do không tìm thấy loại công việc hợp lệ',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Loại công việc xử lý sự cố',
                    'add'
                );

                return $this->sendError('Không tìm thấy loại công việc hợp lệ', 404);
            }

            $taskType->name = $request->get('name');
            $taskType->description = $request->get('description');
            $taskType->employee_number = (int) $request->get('employee_number');
            $taskType->prioritize = $prioritize;

            $taskType->save();

            DB::commit();

            $this->logging(
                'Cập nhật loại công việc xử lý sự cố thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Loại công việc xử lý sự cố',
                'edit',
                $request->get('id')
            );

            return $this->sendResponse($taskType);
        } catch (Exception $e) {
            DB::rollBack();

            $this->logging(
                'Cập nhật loại công việc xử lý sự cố lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Loại công việc xử lý sự cố',
                'edit'
            );

            return $this->sendError('Có lỗi khi cập nhật loại công việc', 500);
        }
    }

    public function delete(Request $request)
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

        try {
            DB::beginTransaction();

            $id = $request->get('id');

            if (!$id) {
                $this->logging(
                    'Xóa loại công việc xử lý sự cố lỗi do dữ liệu đầu vào chưa hợp lệ',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Loại công việc xử lý sự cố',
                    'delete'
                );

                return $this->sendError('Không có định danh loại công việc', 400);
            }

            $taskType = TaskType::where('id', $id)->first();

            if (!$taskType) {
                $this->logging(
                    'Xóa loại công việc xử lý sự cố lỗi do không tìm được loại công việc hợp lệ',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Loại công việc xử lý sự cố',
                    'delete'
                );

                return $this->sendError('Không tìm thấy loại công việc hợp lệ', 404);
            }

            $relation_tasks = Task::where('task_type_id', $id)->get()->count();

            if ($relation_tasks) {
                $this->logging(
                    'Xóa loại công việc xử lý sự cố lỗi do đã có công việc thuộc loại này',
                    $verifyApiToken['id'],
                    $projectType,
                    'failure',
                    'Loại công việc xử lý sự cố',
                    'delete'
                );

                return $this->sendError('Không thể xóa vì đã tồn tại công việc thuộc loại này', 400);
            }

            $taskType->delete();

            DB::commit();

            $this->logging(
                'Xóa loại công việc xử lý sự cố thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Loại công việc xử lý sự cố',
                'delete',
                $id
            );

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            $this->logging(
                'Xóa loại công việc xử lý sự cố lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Loại công việc xử lý sự cố',
                'delete'
            );

            return $this->sendError('Có lỗi khi xóa loại công việc', 500);
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

        if ($projectType == 'ALL_PROJECT') {
            $listing = TaskType::all();
        } else {
            $listing = TaskType::where('project_type', $projectType)->get();
        }

        return $this->sendResponse($listing);
    }
}
