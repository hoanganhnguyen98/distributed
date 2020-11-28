<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Task;
use App\Model\Employee;
use App\Model\History;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request as ApiRequest;
use App\Http\Controllers\Distributed\HistoryController;

class TaskController extends BaseController
{
    protected $incident;

    protected $employees;

    protected $responseMessage;
    protected $statusCode;

    public function __construct(HistoryController $history)
    {
        $this->employees = [];
        $this->history = $history;
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

        if (!$page || !$limit) {
            $tasks = Task::where('type', $type)->get();
        } else {
            $tasks = Task::where('type', $type)->offset(($page - 1) * $limit)->limit($limit)->get();

            $count = Task::where('type', $type)->count();
            $total = ceil($count / $limit);

            $metadata = [
                'total' => (int) $total,
                'page' => (int) $page,
                'limit' => (int) $limit
            ];
        }

        $data = [
            'metadata' => $metadata,
            'tasks' => $tasks
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

        $id = $request->get('id');
        $type = $projectType;

        if (!$id) {
            return $this->sendError('Không có giá trị định danh sự cố', 400);
        }

        $task = Task::where([['id', $id], ['type', $type]])->first();

        if (!$task) {
            return $this->sendError('Công việc xử lý không tồn tại', 404);
        }

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

    public function handler(Request $request)
    {
        $apiToken = $request->header('api-token');
        $projectType = $request->header('project-type');

        if (!$apiToken) {
            return $this->sendError('Thiếu giá trị api-token ở Header', 401);
        }

        if (!$projectType) {
            return $this->sendError('Thiếu giá trị project-type ở Header', 400);
        }

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

        $existedTask = Task::where('incident_id', $incident_id)->first();

        if ($existedTask) {
            if ($existedTask->status == 'doing') {
                $this->responseMessage = 'Sự cố đang trong quá trình xử lý';
                $this->statusCode = 400;
            }

            if ($existedTask->status == 'done') {
                $this->responseMessage = 'Sự cố đã được xử lý xong';
                $this->statusCode = 400;
            }

            return $this->sendError($this->responseMessage, $this->statusCode);
        }

        // checking to get incident information
        $this->incident = $this->incidentChecking($incident_id, $apiToken, $projectType);

        if (!$this->incident) {
            return $this->sendError($this->responseMessage, $this->statusCode);
        }

        // get employee to handler new task
        $captain_id = $this->employeeGetting();

        // create new task
        $task_id = $this->createTask($captain_id);

        foreach ($this->employees as $employee) {
            $this->setNewTask($task_id, $employee, $this->incident['priority']);
        }

        $action = "Tiến hành xử lý sự cố";
        $create_id = Employee::where('employee_id', $verifyApiToken['id'])->first()->id;
        (new HistoryController)->create($task_id, $action, $create_id);

        $isUpdated = $this->updateIncidentStatus($incident_id, $apiToken, $projectType, 1);

        if (!$isUpdated) {
            return $this->sendError($this->responseMessage, $this->statusCode);
        }

        return $this->sendResponse(['task_id' => $task_id]);
    }

    public function createTask($captain_id = null)
    {
        $new_task = Task::create([
            'incident_id' => $this->incident['incident_id'],
            'status' => 'doing',
            'name' => $this->incident['name'],
            'type' => $this->incident['type'],
            'level' => $this->incident['level'],
            'priority' => $this->incident['priority'],
        ]);

        return $new_task->id;
    }

    public function employeeGetting()
    {
        $this->employees = Employee::inRandomOrder()->limit(rand(5,7))->get();

        // foreach ($employees as $key => $employee) {
        //     $employee_id = $employee['result']['id'];

        //     $existedEmployee = Employee::where('employee_id', $employee_id)->first();

        //     if ($existedEmployee) {
        //         $this->employees[] = $existedEmployee;
        //     } else {
        //         $newEmployee = Employee::create([
        //             'employee_id' => $employee_id,
        //             'name' => $employee['result']['full_name'],
        //             'role' => $employee['result']['role'],
        //             'type' => $employee['result']['type'],
        //             'current_id' => null,
        //             'pending_ids' => ','
        //         ]);

        //         $this->employees[] = $newEmployee;
        //     }
        // }

        return 999;
    }

    // khi co mot task moi
    public function setNewTask($task_id, $employee, $priority)
    {
        $employee_id = $employee->employee_id;

        $current_id = $employee->current_id;
        $pending_ids = $employee->pending_ids;

        if ($current_id) {
            $current_task = Task::where([['id', $current_id], ['status', 'doing']])->first();

            if ($current_task) {
                $current_priority = $current_task->priority;

                if ($current_priority >= $priority) {
                    $pending_ids .= $task_id . ',';
                } else {
                    $new_current_id = $task_id;
                    $employee->current_id = $new_current_id;

                    $pending_ids .= $current_id . ',';
                }

                $employee->pending_ids = $pending_ids;
                $employee->save();

                $this->notification('pending', 'add', $employee_id);
            } else {
                $employee->current_id = null;

                $pending_ids .= $task_id . ',';
                $employee->pending_ids = $pending_ids;
                $employee->save();

                $this->setCurrentTask($employee_id);
            }
        } else {
            $pending_ids .= $task_id . ',';
            $employee->pending_ids = $pending_ids;
            $employee->save();

            $this->setCurrentTask($employee_id);
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

            if (count($list) > 0) {
                array_multisort(array_column($list, "priority"), SORT_ASC, array_column($list, "created_at"), SORT_DESC, $list);

                $current_task = end($list);
                $new_current_id = end($list)['id'];

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

    public function incidentChecking($incident_id, $apiToken, $projectType)
    {
        $url = 'https://it4483.cf/api/incidents/'.$incident_id;
        $headers = [
            'api-token' => $apiToken,
            'project-type' => $projectType
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->get($url, [
                'headers' => $headers
            ]);
        } catch (\Throwable $th) {

            if ($th->getCode() == 404) {
                $this->responseMessage = 'Không tìm thấy sự cố theo id đã nhập';
            } else {
                $this->responseMessage = 'Đã có lỗi xảy ra từ khi gọi api kiểm tra sự cố hợp lệ';
            }

            $this->statusCode = $th->getCode();

            return false;
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            if ($data['message']) {
                $this->responseMessage = $data['message'];
                $this->statusCode = $responseStatus;

                return false;
            } else {
                $this->responseMessage = 'Lỗi chưa xác định đã xảy ra khi kiểm tra sự cố hợp lệ';
                $this->statusCode = $responseStatus;

                return false;;
            }
        }

        if ($data['status']['code'] == 1) {
            $this->responseMessage = 'Sự cố đang trong quá trình xử lý';
            $this->statusCode = 400;

            return false;
        }

        if ($data['status']['code'] == 2) {
            $this->responseMessage = 'Sự cố đã được xử lý xong';
            $this->statusCode = 400;

            return false;
        }

        $existed_task = Task::where('incident_id', $incident_id)->first();

        if ($existed_task) {
            $this->responseMessage = 'Sự cố đã được tiến hành xử lý';
            $this->statusCode = 400;

            return false;
        }

        $incident = [
            'incident_id' => $incident_id,
            'name' => $data['name'],
            'type' => $data['type']['type'],
            'level' => $data['level']['name'],
            'priority' => $data['level']['code']
        ];

        return $incident;
    }

    public function updateIncidentStatus($incident_id, $apiToken, $projectType, $status)
    {
        $url = 'https://it4483.cf/api/incidents/'.$incident_id;

        $headers = [
            'api-token' => $apiToken,
            'project-type' => $projectType,
        ];

        $body = [
            'status' => $status
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->put($url, [
                'headers' => $headers,
                'json' => $body,
            ]);
        } catch (\Throwable $th) {
            $this->responseMessage = 'Đã có lỗi xảy ra từ khi gọi api cập nhật trạng thái sự cố';
            $this->statusCode = $th->getCode();

            return false;
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            if ($data['message']) {
                $this->responseMessage = $data['message'];
                $this->statusCode = $responseStatus;

                return false;
            } else {
                $this->responseMessage = 'Lỗi chưa xác định đã xảy ra cập nhật trạng thái sự cố';
                $this->statusCode = $responseStatus;

                return false;
            }
        }

        return true;
    }
}
