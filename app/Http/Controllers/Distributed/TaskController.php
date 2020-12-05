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
                'json' => $body,
            ]);
        } catch (\Throwable $th) {
            $message = 'Đã có lỗi xảy ra từ khi gọi api cập nhật trạng thái sự cố';

            return $this->sendError($message, $th->getCode());
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            if ($data['message']) {
                $message = $data['message'];
            } else {
                $message = 'Lỗi chưa xác định đã xảy ra khi tìm kiếm danh sách sự cố chưa được xử lý';
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

        if ($incident_id) {
            return $this->sendError('Không có giá trị định danh sự cố', 400);
        }

        $tasks = Task::where([['incident_id', $incident_id], ['type',  $projectType]])->get();

        if (!$tasks) {
            return $this->sendError('Không tìm thấy công việc xử lý nào hợp lệ', 404);
        }

        $data = [];
        foreach ($tasks as $task) {
            $task_data = [];

            $task_data['status'] = $task->status;

            $task_type_id = $task->id;
            $task_type = TaskType::where('id', $task_id)->first();
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
                if (strpos($subtask, ',') < 0) {
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
                        'task_type_ids' => $task_type_id,
                        'employee_ids' => $employee_ids
                    ]);

                    $this->setNewTask($apiToken, $projectType, $new_task->id, $employee_ids);
                }
            }

            DB::commit();

            $isUpdated = $this->updateIncidentStatus($incident_id, $apiToken, $projectType, 1);

            if (!$isUpdated) {
                return $this->sendError($this->responseMessage, $this->statusCode);
            }

            return $this->sendResponse();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError('Có lỗi khi tiến hành xử lý sự cố', 500);
        }
    }

    public function setNewTask($apiToken, $projectType, $task_id, $employee_ids)
    {
        if (strpos($employee_ids, ',') > 0) {
            $employees = explode(',', $employee_ids);
        } else {
            $employees = [$employee_ids];
        }

        foreach ($employees as $employee_id) {
            $employee = Employee::where('employee_id', $employee_id)->first();

            if (!$employee) {
                $newEmployee = Employee::create([
                    'employee_id' => $employee_id,
                    'current_id' => $task_id,
                    'pending_ids' => null,
                    'all_ids' => null
                ]);

                if (!$this->changeStatusEmployee($apiToken, $projectType, $employee_id, "BUSY")) {
                    return $this->sendError($this->responseMessage, $this->statusCode);
                }
            } else {
                $pending_ids = $employee->pending_id;
                if ($pending_ids == null) {
                    $employee->pending_ids = $task_id;
                    $employee->save();
                } else {
                    $employee->pending_ids = $pending_ids . ',' . $task_id;
                    $employee->save();
                }

                $this->setCurrentTask($apiToken, $projectType, $employee_id);
            }
        }
    }

    public function setCurrentTask($apiToken, $projectType, $employee_id)
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
                            $setted = true;

                            if (!$this->changeStatusEmployee($apiToken, $projectType, $employee_id, "BUSY")) {
                                return $this->sendError($this->responseMessage, $this->statusCode);
                            }

                            break;
                        }
                    }

                    if ($setted == false) {
                        $employee->current_id = $pending_task_ids[0];

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

                        if (!$this->changeStatusEmployee($apiToken, $projectType, $employee_id, "BUSY")) {
                            return $this->sendError($this->responseMessage, $this->statusCode);
                        }
                    }

                    $this->notification('pending', 'add', $employee_id);
                } else {
                    $employee->current_id = $pending_ids;
                    $employee->pending_ids = null;
                    $employee->save();

                    if (!$this->changeStatusEmployee($apiToken, $projectType, $employee_id, "BUSY")) {
                        return $this->sendError($this->responseMessage, $this->statusCode);
                    }

                    $this->notification('pending', 'add', $employee_id);
                }
            }
        }
    }

    // khi co mot task moi
    // public function setNewTask($task_id, $employee, $priority)
    // {
    //     $employee_id = $employee->employee_id;

    //     $current_id = $employee->current_id;
    //     $pending_ids = $employee->pending_ids;

    //     if ($current_id) {
    //         $current_task = Task::where([['id', $current_id], ['status', '<>' ,'done']])->first();

    //         if ($current_task) {
    //             $current_priority = $current_task->priority;

    //             if ($current_priority >= $priority) {
    //                 $pending_ids .= $task_id . ',';
    //             } else {
    //                 $new_current_id = $task_id;
    //                 $employee->current_id = $new_current_id;

    //                 $old_all_ids = $employee->all_ids;
    //                 $employee->all_ids = $old_all_ids . $new_current_id . ',';

    //                 $pending_ids .= $current_id . ',';
    //             }

    //             $employee->pending_ids = $pending_ids;
    //             $employee->save();

    //             $this->notification('pending', 'add', $employee_id);
    //         } else {
    //             $employee->current_id = null;

    //             $pending_ids .= $task_id . ',';
    //             $employee->pending_ids = $pending_ids;
    //             $employee->save();

    //             $this->setCurrentTask($employee_id);
    //         }
    //     } else {
    //         $pending_ids .= $task_id . ',';
    //         $employee->pending_ids = $pending_ids;
    //         $employee->save();

    //         $this->setCurrentTask($employee_id);
    //     }
    // }

    // khi nhan vien da hoan thanh mot task (task hien tai trong)
    // public function setCurrentTask($employee_id)
    // {
    //     $employee = Employee::where('employee_id', $employee_id)->first();

    //     $pending_ids = $employee->pending_ids;

    //     // if pending task not null
    //     if (strlen($pending_ids) > 1) {
    //         $pending_ids_array = array_slice(explode(',', $pending_ids), 1, -1);

    //         $list = [];
    //         foreach ($pending_ids_array as $id) {
    //             $task = Task::where([['id', $id], ['status', '<>' ,'done']])->first();

    //             if ($task) {
    //                 $list[] = [
    //                     'id' => $id,
    //                     'priority' => $task->priority,
    //                     'created_at' => $task->created_at
    //                 ];
    //             } else {
    //                 // remove id from pending list
    //                 $pending_ids = str_replace($id . ',', '', $pending_ids);

    //                 $this->notification('pending', 'remove', $employee_id);
    //             }
    //         }

    //         if (count($list) > 0) {
    //             array_multisort(array_column($list, "priority"), SORT_ASC, array_column($list, "created_at"), SORT_DESC, $list);

    //             $current_task = end($list);
    //             $new_current_id = end($list)['id'];

    //             $employee->current_id = $new_current_id;

    //             $old_all_ids = $employee->all_ids;
    //             $employee->all_ids = $old_all_ids . $new_current_id . ',';

    //             $employee->save();

    //             $this->notification('current', 'push', $employee_id);

    //             $pending_ids = str_replace($new_current_id . ',', '', $pending_ids);

    //             $this->notification('pending', 'remove', $employee_id);
    //         }

    //         $employee->pending_ids = $pending_ids;
    //         $employee->save();
    //     }
    // }

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
            $this->responseMessage = 'Đã có lỗi xảy ra từ khi gọi api cập nhật trạng thái nhân viên';
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
                $this->responseMessage = 'Lỗi chưa xác định đã xảy ra cập nhật trạng thái nhân viên';
                $this->statusCode = $responseStatus;

                return false;
            }
        }

        return true;
    }

    public function handlerIncident($incident_id, $apiToken, $projectType)
    {
        $this->incident = $this->incidentChecking($incident_id, $apiToken, $projectType);

        if ($this->incident) {
            // get employee to handler new task
            $employeeGetting = $this->employeeGetting();

            // create new task
            $task_id = $this->createTask();

            foreach ($employeeGetting as $employee) {
                $this->setNewTask($task_id, $employee, $this->incident['priority']);
            }

            $this->setCaptainForTask($task_id);

            $isUpdated = $this->updateIncidentStatus($incident_id, $apiToken, $projectType, 1);

            if (!$isUpdated) {
                return $this->sendError($this->responseMessage, $this->statusCode);
            }

            return $this->sendResponse(['task_id' => $task_id]);
        }
    }
}
