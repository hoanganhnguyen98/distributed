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
            'Access-Control-Allow-Origin' => '*',
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
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    public function notification($type, $action, $employee_id)
    {

    }

    public function callApi($method, $url, $header)
    {
        $client = new \GuzzleHttp\Client();
        $request = new ApiRequest($method, $url, $header);
        $response = $client->send($request);

        return $response;
    }
}
