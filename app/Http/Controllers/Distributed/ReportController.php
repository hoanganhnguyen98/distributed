<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Report;
use Carbon\Carbon;

class ReportController extends BaseController
{
    public function listing(Request $request)
    {
        $type_id = $request->get('id');

        if(!$type_id) {
            return $this->sendError('Không có giá trị định danh nhóm sự cố', 400);
        }

        $page = $request->get('page');
        $limit = $request->get('limit');
        $metadata = [];

        if (!$page || !$limit) {
            $list = Report::where('type',$type_id)->get();
        } else {
            $list = Report::where('type',$type_id)->offset(($page - 1) * $limit)->limit($limit)->get();

            $count = Report::where('type',$type_id)->count();
            $total = ceil($count / $limit);

            $metadata = [
                'total' => (int) $total,
                'page' => (int) $page,
                'limit' => (int) $limit
            ];
        }

        $data[] = [
            'metadata' => $metadata,
            'list' => $list
        ];

        return $this->sendResponse($data);
    }
}
