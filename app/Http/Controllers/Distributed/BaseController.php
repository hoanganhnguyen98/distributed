<?php
namespace App\Http\Controllers\Distributed;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($data = [], $message = 'Thành công')
    {
    	$response = [
            'data' => $data,
            'error' => [
                "code" => 200,
                "message" => $message
            ]
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($message = 'Lỗi chưa xác định', $code = 404)
    {
    	$response = [
            'data' => [],
            'error' => [
                "code" => $code,
                "message" => $message
            ]
        ];

        return response()->json($response, $code);
    }
}
