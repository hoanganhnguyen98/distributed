<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Task;
use App\Model\Support;
use App\Model\Employee;
use Carbon\Carbon;
use App\Http\Controllers\Distributed\TaskController as TaskController;

class IncidentController extends BaseController
{
    public function listing(Request $request, $status = 0)
    {
        $apiToken = $request->header('api-token');
        $projectType = $request->header('project-type');

        $verifyApiToken = $this->verifyApiToken($apiToken, $projectType);

        if(empty($verifyApiToken)) {
            return $this->sendError('Đã có lỗi xảy ra từ khi gọi api verify token', 401);
        } else {
            $statusCode = $verifyApiToken['code'];

            if ($statusCode != 200) {
                return $this->sendError($message, $th->getCode());
            }
        }

        $url = 'https://it4483.cf/api/incidents/search';

        $headers = [
            'api-token' => $apiToken,
            'project-type' => $projectType,
        ];

        $body = [
            'status' => $status
        ];

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
}
