<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Support;
use Carbon\Carbon;

class SupportController extends BaseController
{
    public function Listing(Request $request)
    {
        $type_id = $request->get('id');

        if(!$type_id) {
            return $this->sendError('Không có giá trị định danh nhóm sự cố', 400);
        }

        $page = $request->get('page');
        $limit = $request->get('limit');
        $metadata = [];

        if (!$page || !$limit) {
            $lists = Support::where('type',$type_id)->get();
        } else {
            $lists = Support::where('type',$type_id)->offset(($page - 1) * $limit)->limit($limit)->get();

            $count = Support::where('type',$type_id)->count();
            $total = ceil($count / $limit);

            $metadata = [
                'total' => (int) $total,
                'page' => (int) $page,
                'limit' => (int) $limit
            ];
        }

        $data[] = [
            'metadata' => $metadata,
            'lists' => $lists
        ];

        return $this->sendResponse($data);
    }
}
