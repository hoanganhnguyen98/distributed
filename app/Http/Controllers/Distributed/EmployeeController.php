<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Employee;
use App\Model\Task;
use App\Model\TaskType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeController extends BaseController
{
    public function active(Request $request, $task_id)
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

        $task_id = (int) $task_id;

        if (!$task_id) {
            $this->logging(
                'Xác nhận công việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Công việc xử lý sự cố'
            );

            return $this->sendError('Không có giá trị định danh công việc', 400);
        }

        $employee_id = $verifyApiToken['id'];
        $employee = Employee::where('employee_id', $employee_id)->first();

        if ($employee->current_id !== $task_id) {
            $this->logging(
                'Xác nhận công việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Công việc xử lý sự cố'
            );

            return $this->sendError('Định danh công việc không trùng với công việc hiện tại', 403);
        }

        $task = Task::where('id', $task_id)->first();

        if (!$task) {
            $this->logging(
                'Xác nhận công việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Công việc xử lý sự cố'
            );

            return $this->sendError('Công việc xử lý không tồn tại', 404);
        }

        if ($task->status == 'done') {
            $this->logging(
                'Xác nhận công việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Công việc xử lý sự cố'
            );

            return $this->sendError('Công việc xử lý đã hoàn tất', 403);
        }

        $employee_ids = $task->employee_ids;

        if (strpos($employee_ids, ',') > 0) {
            $employee_area = explode(',', $employee_ids);
        } else {
            $employee_area = [$employee_ids];
        }

        if (!in_array($employee_id, $employee_area)) {
            $this->logging(
                'Xác nhận công việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Công việc xử lý sự cố'
            );

            return $this->sendError('Không thuộc phạm vi quản lý công việc', 403);
        }

        $active_ids = $task->active_ids;
        $active_task = false;

        if ($active_ids) {
            if (strpos($active_ids, ',') > 0) {
                if (in_array($employee_id, explode(',', $active_ids))) {
                    $active_task = true;
                }
            } else {
                if ($employee_id === (int) $active_ids) {
                    $active_task = true;
                }
            }
        }

        if ($active_task) {
            $this->logging(
                'Xác nhận công việc lỗi do dữ liệu đầu vào chưa hợp lệ',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Công việc xử lý sự cố'
            );

            return $this->sendError('Công việc đã được xác nhận từ trước', 403);
        }

        if ($active_ids) {
            $new_active_ids = $active_ids . ',' . $employee_id;
        } else {
            $new_active_ids = $employee_id;
        }

        try {
            DB::beginTransaction();

            $task->active_ids = $new_active_ids;
            $task->status = 'doing';
            $task->save();

            DB::commit();

            $this->logging(
                'Xác nhận công việc thành công',
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Công việc xử lý sự cố'
            );

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollback();

            $this->logging(
                'Xác nhận công việc lỗi chưa xác định',
                $verifyApiToken['id'],
                $projectType,
                'failure',
                'Công việc xử lý sự cố'
            );

            return $this->sendError('Đã có lỗi xảy ra khi xác nhận công việc', 500);
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

        $type = $projectType;

        $page = $request->get('page');
        $limit = $request->get('limit');
        $metadata = [];

        if ($type == 'ALL_PROJECT') {
            $employees = Employee::all();
        } else {
            $employees = Employee::where('type',$type)->get();
        }

        // if (!$page || !$limit) {
        //     $employees = Employee::where('type',$type)->get();
        // } else {
        //     $employees = Employee::where('type',$type)->offset(($page - 1) * $limit)->limit($limit)->get();

        //     $count = Employee::where('type',$type)->count();
        //     $total = ceil($count / $limit);

        //     $metadata = [
        //         'total' => (int) $total,
        //         'page' => (int) $page,
        //         'limit' => (int) $limit
        //     ];
        // }

        $list = [];
        foreach ($employees as $key => $employee) {
            $employee_id = $employee->employee_id;
            $user = $this->getUserInformation($employee_id, $apiToken, $projectType);

            $current_id = $employee->current_id;
            $current_task = Task::where([['id', $current_id], ['status', '<>' ,'done']])->first();

            $current_task_type = null;
            if ($current_task) {
                $current_task_type_id = $current_task->task_type_id;
                $current_task_type = TaskType::where('id', $current_task_type_id)->first();
            }

            $list[] = [
                'employee' => $user,
                'current_task' => $current_task,
                'current_task_type' => $current_task_type
            ];
        }

        $data = [
            'metadata' => $metadata,
            'list' => $list
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

        $employee_id  = $verifyApiToken['id'];
        $user = $this->getUserInformation($employee_id, $apiToken, $projectType);

        $existedEmployee = Employee::where('employee_id', $employee_id)->first();

        if (!$existedEmployee) {
            $newEmployee = Employee::create([
                'employee_id' => $employee_id,
                'current_id' => null,
                'pending_ids' => null,
                'all_ids' => null,
                'type' => $projectType
            ]);

            $data = [
                'employee' => $user,
                'current_task' => null,
                'active_current_task' => false,
                'pending_tasks' => []
            ];

            return $this->sendResponse($data);
        }

        $current_id = $existedEmployee->current_id;

        if ($current_id) {
            $current_task = Task::where([['id', $current_id], ['status', '<>' ,'done']])->first();
        } else {
            $current_task = null;
        }

        $current_task_info = [];
        if ($current_task) {
            $current_task_type_id = $current_task->task_type_id;
            $current_task_type = TaskType::where('id', $current_task_type_id)->first();

            $current_task_info['id'] = $current_task->id;
            $current_task_info['status'] = $current_task->status;
            $current_task_info['task_type'] = $current_task_type;
        }

        $active_task = false;
        if ($current_task) {
            $active_ids = $current_task->active_ids;

            if ($active_ids) {
                if (strpos($active_ids, ',') > 0) {
                    if (in_array($employee_id, explode(',', $active_ids))) {
                        $active_task = true;
                    }
                } else {
                    if ($employee_id === (int) $active_ids) {
                        $active_task = true;
                    }
                }
            }
        }

        $pending_ids = $existedEmployee->pending_ids;
        $pending_tasks = [];

        if ($pending_ids) {
            if (strpos($pending_ids, ',') > 0) {
                $pending_ids_list = explode(',', $pending_ids);
            } else {
                $pending_ids_list = [$pending_ids];
            }

            foreach ($pending_ids_list as $id) {
                $task = Task::where([['id', $id], ['status', '<>' ,'done']])->first();

                if ($task) {
                    $task_data = [];

                    $task_data['id'] = $task->id;
                    $task_data['status'] = $task->status;

                    $task_type_id = $task->task_type_id;
                    $task_type = TaskType::where('id', $task_type_id)->first();
                    $task_data['task_type'] = $task_type;

                    $pending_tasks[] = $task_data;
                }
            }
        }

        $data = [
            'employee' => $user,
            'current_task' => count($current_task_info) ? $current_task_info : null,
            'active_current_task' => $active_task,
            'pending_tasks' => $pending_tasks
        ];

        return $this->sendResponse($data);
    }

    public function login(Request $request)
    {
        $username = $request->get('username');

        if (!$username) {
            return $this->sendError('Thiếu giá trị username', 400);
        }

        $password = $request->get('password');

        if (!$password) {
            return $this->sendError('Thiếu giá trị password', 400);
        }

        $url = 'https://distributed.de-lalcool.com/api/login';

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $body = [
            "username" => $username,
            "password" => $password
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);
        } catch (\Throwable $th) {
            $message = 'Đã có lỗi xảy ra từ khi gọi api login';

            return $this->sendError($message, $th->getCode());
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            if ($data['message']) {
                $message = $data['message'];
            } else {
                $message = 'Lỗi chưa xác định đã xảy ra khi gọi api login';
            }

            return $this->sendError($message, $responseStatus);
        }

        $user = $data['result'];
        $employee_id = $user['id'];

        $existedEmployee = Employee::where('employee_id', $employee_id)->first();

        if (!$existedEmployee) {
            $newEmployee = Employee::create([
                'employee_id' => $employee_id,
                'current_id' => null,
                'pending_ids' => [],
                'all_ids' => null,
                'type' => $user['type']
            ]);

            $data = [
                'employee' => $user,
                'current_task' => null,
                'active_current_task' => false,
                'pending_tasks' => []
            ];

            return $this->sendResponse($data);
        }

        $current_id = $existedEmployee->current_id;

        if ($current_id) {
            $current_task = Task::where([['id', $current_id], ['status', '<>' ,'done']])->first();
        } else {
            $current_task = null;
        }

        $current_task_info = [];
        if ($current_task) {
            $current_task_type_id = $current_task->task_type_id;
            $current_task_type = TaskType::where('id', $current_task_type_id)->first();

            $current_task_info['id'] = $current_task->id;
            $current_task_info['status'] = $current_task->status;
            $current_task_info['task_type'] = $current_task_type;
        }

        $active_task = false;
        if ($current_task) {
            $active_ids = $current_task->active_ids;

            if ($active_ids) {
                if (strpos($active_ids, ',') > 0) {
                    if (in_array($employee_id, explode(',', $active_ids))) {
                        $active_task = true;
                    }
                } else {
                    if ($employee_id === (int) $active_ids) {
                        $active_task = true;
                    }
                }
            }
        }

        $pending_ids = $existedEmployee->pending_ids;
        $pending_tasks = [];

        if ($pending_ids) {
            if (strpos($pending_ids, ',') > 0) {
                $pending_ids_list = explode(',', $pending_ids);
            } else {
                $pending_ids_list = [$pending_ids];
            }

            foreach ($pending_ids_list as $id) {
                $task = Task::where([['id', $id], ['status', '<>' ,'done']])->first();

                if ($task) {
                    $task_data = [];

                    $task_data['id'] = $task->id;
                    $task_data['status'] = $task->status;

                    $task_type_id = $task->task_type_id;
                    $task_type = TaskType::where('id', $task_type_id)->first();
                    $task_data['task_type'] = $task_type;

                    $pending_tasks[] = $task_data;
                }
            }
        }


        $data = [
            'employee' => $user,
            'current_task' => count($current_task_info) ? $current_task_info : null,
            'active_current_task' => $active_task,
            'pending_tasks' => $pending_tasks
        ];

        return $this->sendResponse($data);
    }

    public function getUserInformation($employee_id, $apiToken, $projectType)
    {
        $url = 'https://distributed.de-lalcool.com/api/user/'. $employee_id;

        $headers = [
            'token' => $apiToken,
            'project-type' => $projectType,
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->get($url, [
                'headers' => $headers,
            ]);
        } catch (\Throwable $th) {
            return null;
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            if ($data['message']) {
                return null;
            } else {
                return null;
            }
        }

        return $data['result'];
    }
}
