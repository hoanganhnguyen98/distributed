<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Support;
use App\Model\Report;
use App\Model\Task;
use App\Model\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Cloudder;

class SupportController extends BaseController
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

        $task_id = $request->get('id');

        if(!$task_id) {
            return $this->sendError('Không có giá trị định danh báo cáo kết quả', 400);
        }

        $task = Task::where([['id', $task_id], ['status', '<>', 'done']])->first();

        if (!$task) {
            return $this->sendError('Định danh báo cáo kết quả không hợp lệ', 404);
        }

        $create_id = Employee::where('employee_id', $verifyApiToken['id'])->first()->id;

        if ($task->captain_id != $create_id) {
            return $this->sendError('Bạn không có quyền gửi báo cáo kết quả', 403);
        }

        $rules = [
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'number' => ['required'],
            'image' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('Thông tin gửi đi không hợp lệ', 400);
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')){
                // create path to store in cloud
                $public_id = "ninja_restaurant/distributed/supports/" . ($create_id . '_' . Carbon::now()->timestamp);
                // upload to cloud
                Cloudder::upload($request->file('image'), $public_id);
                // get url of image
                $resize = array("width" => null, "height" => null, "crop" => null);
                $img_url = Cloudder::show($public_id, $resize);
            }

            Support::create([
                'title' => $request->get('title'),
                'content' => $request->get('content'),
                'task_id' => $task_id,
                'image' => $img_url,
                'status' => 'waiting',
                'type' => $projectType,
                'expected_number' => $request->get('number'),
                'create_id' => $create_id
            ]);

            DB::commit();

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError('Đã có lỗi xảy ra khi tạo yêu cầu hỗ trợ', 500);
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

        $type_id = $projectType;

        $page = $request->get('page');
        $limit = $request->get('limit');
        $metadata = [];

        if (!$page || !$limit) {
            $list = Support::where('type',$type_id)->get();
        } else {
            $list = Support::where('type',$type_id)->offset(($page - 1) * $limit)->limit($limit)->get();

            $count = Support::where('type',$type_id)->count();
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
}
