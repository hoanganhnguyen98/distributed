<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Task;
use App\Model\TaskType;
use App\Model\Employee;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request as ApiRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Distributed\EmployeeController;

class TaskController extends BaseController
{
    protected $incident;

    protected $employees;

    protected $responseMessage;
    protected $statusCode;

    public function __construct()
    {
        $this->employees = [];
    }

    public function getEmployeeListing(Request $request)
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

        $url = 'https://distributed.de-lalcool.com/api/user?page_id=0&page_size=20&filters=role=INCIDENT_STAFF,status=ACTIVE';

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
            $message = 'Đã có lỗi xảy ra từ khi gọi api lấy danh sách user theo id';

            return $this->sendError($message, $th->getCode());
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            if ($data['message']) {
                $message = $data['message'];
            } else {
                $message = 'Lỗi chưa xác định đã xảy ra khi lấy danh sách user theo id';
            }

            return $this->sendError($message, $responseStatus);
        }

        return $data['result'];
    }

    public function incidentListing(Request $request)
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

        $url = 'https://it4483.cf/api/incidents/search';

        $headers = [
            'api-token' => $apiToken,
            'project-type' => $projectType,
        ];

        // $body = [
        //     'status' => $status
        // ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                // 'json' => $body,
            ]);
        } catch (\Throwable $th) {
            $message = 'Đã có lỗi xảy ra từ khi gọi api lấy danh sách sự cố';

            return $this->sendError($message, $th->getCode());
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            if ($data['message']) {
                $message = $data['message'];
            } else {
                $message = 'Lỗi chưa xác định đã xảy ra khi gọi api lấy danh sách sự cố';
            }

            return $this->sendError($message, $responseStatus);
        }

        $incidents = $data['incidents'];

        return $this->sendResponse($incidents);
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

        $incident_id = $request->get('incident_id');

        if (!$incident_id) {
            return $this->sendError('Không có giá trị định danh sự cố', 400);
        }

        $tasks = Task::where('incident_id', $incident_id)->get();

        if (!count($tasks)) {
            return $this->sendError('Không tìm thấy công việc xử lý nào hợp lệ', 404);
        }

        $data = [];
        foreach ($tasks as $task) {
            $task_data = [];

            $task_data['status'] = $task->status;

            $task_type_id = $task->task_type_id;
            $task_type = TaskType::where('id', $task_type_id)->first();
            $task_data['task_type'] = $task_type;

            $employee_ids = $task->employee_ids;
            if (strpos($employee_ids, ',') > 0) {
                $ids = explode(',', $employee_ids);

                $formatId = '';
                foreach ($ids as $id) {
                    $formatId .= $id . ';';
                }

                $format = "{" . rtrim($formatId, "; ") . "}";
            } else {
                $format = "{" . $employee_ids . "}";
            }

            $employees = $this->getEmployeeInTask($apiToken, $projectType, $format);
            $task_data['employees'] = $employees;

            $data[] = $task_data;
        }

        return $this->sendResponse($data);
    }

    public function getEmployeeInTask($apiToken, $projectType, $ids)
    {
        $url = 'https://distributed.de-lalcool.com/api/user?filters=id='.$ids;

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
            $message = 'Đã có lỗi xảy ra từ khi gọi api lấy danh sách user theo id';

            return $this->sendError($message, $th->getCode());
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            if ($data['message']) {
                $message = $data['message'];
            } else {
                $message = 'Lỗi chưa xác định đã xảy ra khi lấy danh sách user theo id';
            }

            return $this->sendError($message, $responseStatus);
        }

        return $data['result'];
    }

    public function handler(Request $request)
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

        $incident_id = $request->get('incident_id');

        if (!$incident_id) {
            return $this->sendError('Không có giá trị định danh sự cố', 400);
        }

        // checking to get incident information
        $this->incident = $this->incidentChecking($incident_id, $apiToken, $projectType);

        if (!$this->incident) {
            return $this->sendError($this->responseMessage, $this->statusCode);
        }

        $incidentType = $this->incident['type'];
        try {
            DB::beginTransaction();

            $rules = [
                'list' => ['required', 'string'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError('Giá trị list chưa hợp lệ', 400);
            }

            $list = $request->get('list');

            if (strpos($list, ';') > 0) {
                $subtasks = explode(';', $list);
            } else {
                $subtasks = [$list];
            }

            foreach ($subtasks as $subtask) {
                if (strpos($subtask, ',') <= 0) {
                    return $this->sendError('Giá trị list chưa hợp lệ', 400);
                } else {
                    $task = explode(',', $subtask);
                    $task_type_id = (int) $task[0];
                    $employees = '';

                    foreach ($task as $key => $value) {
                        if ($key !== 0) {
                            $employees .= $value . ',';
                        }
                    }

                    $employee_ids = rtrim($employees, ", ");

                    $new_task = Task::create([
                        'incident_id' => $incident_id,
                        'status' => 'pending',
                        'task_type_id' => $task_type_id,
                        'employee_ids' => $employee_ids,
                        'active_ids'  => null
                    ]);

                    if (strpos($employee_ids, ',') > 0) {
                        $employees = explode(',', $employee_ids);
                    } else {
                        $employees = [$employee_ids];
                    }

                    $checkAllUser = true;
                    foreach ($employees as $employee_id) {
                        $isValidUser = $this->userChecking($employee_id, $apiToken, $projectType);

                        if (!$isValidUser) {
                            $checkAllUser = false;

                            break;
                        }
                    }

                    if (!$checkAllUser) {
                        return $this->sendError($this->responseMessage, $this->statusCode);
                    }

                    $this->setNewTask($apiToken, $projectType, $new_task->id, $employees, $verifyApiToken['id'], $incidentType);
                }
            }

            DB::commit();

            $isUpdated = $this->updateIncidentStatus($incident_id, $apiToken, $projectType, 1);

            if (!$isUpdated) {
                return $this->sendError($this->responseMessage, $this->statusCode);
            }

            $this->logging(
                'Tiến hành xử lý thành công sự cố có id là ' . $incident_id,
                $verifyApiToken['id'],
                $projectType,
                'success',
                'Xử lý sự cố',
                'add',
                $incident_id
            );

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError('Có lỗi khi tiến hành xử lý sự cố', 500);
        }
    }

    public function setNewTask($apiToken, $projectType, $task_id, $employees, $admin_id, $incidentType)
    {
        foreach ($employees as $employee_id) {
            $employee = Employee::where('employee_id', $employee_id)->first();

            if (!$employee) {
                $newEmployee = Employee::create([
                    'employee_id' => $employee_id,
                    'current_id' => $task_id,
                    'pending_ids' => null,
                    'all_ids' => null,
                    'type' => $incidentType
                ]);

                $this->createUserMeta(
                    $apiToken,
                    $incidentType,
                    $admin_id,
                    $employee_id,
                    "Yêu cầu nhân viên xử lý công việc",
                    "DOING",
                    $task_id,
                    $incidentType
                );

                $this->changeStatusEmployee($apiToken, $projectType, $employee_id, "BUSY");
            } else {
                $pending_ids = $employee->pending_id;
                if ($pending_ids == null) {
                    $employee->pending_ids = $task_id;
                    $employee->save();
                } else {
                    $employee->pending_ids = $pending_ids . ',' . $task_id;
                    $employee->save();
                }

                $this->setCurrentTask($apiToken, $projectType, $employee_id, $admin_id, $incidentType);
            }
        }
    }

    public function setCurrentTask($apiToken, $projectType, $employee_id, $admin_id, $incidentType)
    {
        $employee = Employee::where('employee_id', $employee_id)->first();

        if ($employee->current_id == null) {
            $pending_ids = $employee->pending_ids;

            if ($pending_ids) {
                if (strpos($pending_ids, ',') > 0) {
                    $pending_task_ids = explode(',', $pending_ids);

                    $setted = false;
                    foreach ($pending_task_ids as $key => $task_id) {
                        $task = Task::where('id', $task_id)->first();
                        $prioritize = TaskType::where('id', $task->task_type_id)->first();

                        if ($prioritize) {
                            $employee->current_id = $task_id;

                            unset($pending_task_ids[$key]);

                            if (!empty($pending_task_ids)) {
                                $new_pending_ids = '';

                                foreach ($pending_task_ids as $id) {
                                    $new_pending_ids .= $id . ',';
                                }

                                $employee->pending_ids = rtrim($new_pending_ids, ", ");
                            } else {
                                $employee->pending_ids = null;
                            }

                            $employee->save();

                            $this->createUserMeta(
                                $apiToken,
                                $projectType,
                                $admin_id,
                                $employee_id,
                                "Yêu cầu nhân viên xử lý công việc",
                                "DOING",
                                $task_id,
                                $incidentType
                            );

                            $setted = true;

                            $this->changeStatusEmployee($apiToken, $projectType, $employee_id, "BUSY");

                            break;
                        }
                    }

                    if ($setted == false) {
                        $new_current_id = $pending_task_ids[0];
                        $employee->current_id = $new_current_id;

                        unset($pending_task_ids[$key]);

                        if (!empty($pending_task_ids)) {
                            $new_pending_ids = '';

                            foreach ($pending_task_ids as $id) {
                                $new_pending_ids .= $id . ',';
                            }

                            $employee->pending_ids = rtrim($new_pending_ids, ", ");
                        } else {
                            $employee->pending_ids = null;
                        }

                        $employee->save();

                        $this->createUserMeta(
                            $apiToken,
                            $projectType,
                            $admin_id,
                            $employee_id,
                            "Yêu cầu nhân viên xử lý công việc",
                            "DOING",
                            $new_current_id,
                            $incidentType
                        );

                        $this->changeStatusEmployee($apiToken, $projectType, $employee_id, "BUSY");
                    }

                    $this->notification(
                        $apiToken,
                        $projectType,
                        $admin_id,
                        [$employee_id],
                        $new_current_id,
                        "http://14.248.5.197:8010/handle-problem",
                        "Thông báo công việc mới",
                        1
                    );
                } else {
                    $employee->current_id = $pending_ids;
                    $employee->pending_ids = null;
                    $employee->save();

                    $this->createUserMeta(
                        $apiToken,
                        $projectType,
                        $admin_id,
                        $employee_id,
                        "Yêu cầu nhân viên xử lý công việc",
                        "DOING",
                        $pending_ids,
                        $incidentType
                    );

                    $this->changeStatusEmployee($apiToken, $projectType, $employee_id, "BUSY");

                    $this->notification(
                        $apiToken,
                        $projectType,
                        $admin_id,
                        [$employee_id],
                        $pending_ids,
                        "http://14.248.5.197:8010/handle-problem",
                        "Thông báo công việc mới",
                        1
                    );
                }
            }
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

    public function changeStatusEmployee($apiToken, $projectType, $user_id, $status)
    {
        $url = 'https://distributed.de-lalcool.com/api/user/update-status';

        $headers = [
            'token' => $apiToken,
            'project-type' => $projectType,
        ];

        $body = [
            'user_id' => $user_id,
            'status_activation' => $status
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->patch($url, [
                'headers' => $headers,
                'json' => $body,
            ]);
        } catch (\Throwable $th) {
        }
    }

    public function userChecking($employee_id, $apiToken, $projectType)
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
            $this->responseMessage = 'Đã có lỗi xảy ra từ khi gọi api kiểm tra id nhân viên';
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
                $this->responseMessage = 'Lỗi chưa xác định đã xảy ra khi gọi api kiểm tra id nhân viên';
                $this->statusCode = $responseStatus;

                return false;
            }
        }

        if ($data['result'] === null) {
            $this->responseMessage = 'Nhân viên ID ' . $employee_id . ' của loại dự án ' . $projectType . ' không tồn tại';
            $this->statusCode = 400;

            return false;
        }

        return true;
    }

    public function getTaskById(Request $request, $id)
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

        if (!$id) {
            return $this->sendError('Không có id của công việc xử lý sự cố', 400);
        }

        $task = Task::where('id', $id)->first();

        if (!$task) {
            return $this->sendError('Không tìm thấy công việc xử lý sự cố hợp lệ', 404);
        }

        $result['id'] = $id;
        $result['status'] = $task->status;

        $task_type_id = $task->task_type_id;
        $task_type = TaskType::where('id', $task_type_id)->first();
        $result['task_type'] = $task_type;

        $employee_ids = $task->employee_ids;

        $employees = [];
        if ($employee_ids) {
            if (strpos($employee_ids, ',') > 0) {
                $array_id = explode(',', $employee_ids);
            } else {
                $array_id = [$employee_ids];
            }

            foreach ($array_id as $employee_id) {
                $employees[] = (new EmployeeController)->getUserInformation($employee_id, $apiToken, $projectType);
            }
        }

        $result['employees'] = $employees;

        return $this->sendResponse($result);
    }
}
