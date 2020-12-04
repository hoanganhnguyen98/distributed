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
                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 400);
            }

            if ((int) $request->get('prioritize') == 1) {
                $prioritize = true;
            } else {
                $prioritize = false;
            }

            // dd($prioritize);

            $newTaskType = TaskType::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'employee_number' => (int) $request->get('employee_number'),
                'project_type' => $projectType,
                'prioritize' => $prioritize,
                'create_id' => $create_id
            ]);

            DB::commit();

            return $this->sendResponse($newTaskType);
        } catch (Exception $e) {
            DB::rollBack();

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
                'name' => ['string'],
                'description' => ['string'],
                'employee_number' => ['numeric'],
                'prioritize' => ['bool']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 400);
            }

            $taskType = TaskType::where('id', $request->get('id'))->first();

            if (!$taskType) {
                return $this->sendError('Không tìm thấy loại công việc hợp lệ', 404);
            }

            $updates = ['name', 'description', 'employee_number', 'prioritize'];

            foreach ($updates as $update) {
                if ($request->get($update)) {
                    $taskType->{$update} = $request->get($update);
                }
            }

            $taskType->save();

            DB::commit();

            return $this->sendResponse($taskType);
        } catch (Exception $e) {
            DB::rollBack();

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

            $rules = [
                'id' => ['required']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Dữ liệu đầu vào chưa hợp lệ', 400);
            }

            $taskType = TaskType::where('id', $request->get('id'))->first();

            if (!$taskType) {
                return $this->sendError('Không tìm thấy loại công việc hợp lệ', 404);
            }

            $taskType->delete();

            DB::commit();

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

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

        $listing = TaskType::where('project_type', $projectType)->get();

        return $this->sendResponse($listing);
    }
}
