<?php
namespace App\Http\Controllers\Distributed;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use GuzzleHttp\Psr7\Request as ApiRequest;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($data = [])
    {
    	$response = $data;

        return response()->json($response, 200)->withHeaders([
            'Access-Control-Allow-Headers' => 'Authorization, Origin, X-Requested-With, Content-Type, Accept, DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Range',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS, PUT, DELETE, HEAD',
            // 'Access-Control-Allow-Origin' => '*',
        ]);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($message = 'Lỗi chưa xác định', $code = 404)
    {
    	$response = [
            'error' => [
                "message" => $message
            ]
        ];

        return response()->json($response, $code)->withHeaders([
            'Access-Control-Allow-Headers' => 'Authorization, Origin, X-Requested-With, Content-Type, Accept, DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Range',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS, PUT, DELETE, HEAD',
            // 'Access-Control-Allow-Origin' => '*',
        ]);
    }

    public function notification($type, $action, $employee_id)
    {

    }

    public function verifyApiToken($apiToken = null, $projectType = null)
    {
        if (!$apiToken) {
            $verifyApiToken['message'] = 'Thiếu giá trị api-token ở Header';
            $verifyApiToken['code'] = 401;

            return $verifyApiToken;
        }

        if (!$projectType) {
            $verifyApiToken['message'] = 'Thiếu giá trị project-type ở Header';
            $verifyApiToken['code'] = 400;

            return $verifyApiToken;
        }

        $url = 'https://distributed.de-lalcool.com/api/verify-token';
        $headers = [
            'api-token' => $apiToken,
            'project-type' => $projectType
        ];

        $client = new \GuzzleHttp\Client();

        $verifyApiToken = [];

        try {
            $response = $client->get($url, [
                'headers' => $headers
            ]);
        } catch (\Throwable $th) {
            if ($th->getCode() == 401) {
                $verifyApiToken['message'] = 'User token hoặc loại dự án không đúng!';
            } else {
                $verifyApiToken['message'] = 'Đã có lỗi xảy ra từ khi gọi api verify token';
            }

            $verifyApiToken['code'] = $th->getCode();

            return $verifyApiToken;
        }

        $responseStatus = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents(), true);

        if ($responseStatus !== 200) {
            if ($data['message']) {
                $verifyApiToken['message'] = 'Đã có lỗi xảy ra từ khi gọi api verify token';
            } else {
                $verifyApiToken['message'] = 'Lỗi chưa xác định đã xảy ra khi verify token';
            }
        } else {
            $verifyApiToken['id'] = $data['result']['id'];
            $verifyApiToken['name'] = $data['result']['full_name'];
            $verifyApiToken['role'] = $data['result']['role'];
        }

        $verifyApiToken['code'] = $responseStatus;

        return $verifyApiToken;
    }

    public function callApi($method, $url, $header)
    {
        $client = new \GuzzleHttp\Client();
        $request = new ApiRequest($method, $url, $header);
        $response = $client->send($request);

        return $response;
    }
}
