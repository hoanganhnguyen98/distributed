<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Task;
use Carbon\Carbon;

class TaskController extends BaseController
{
    // param: id sự cố
    public function workHandler(Request $request)
    {
        $id = $request->get('id');

        if (!$id) {
            return $this->sendError('Không có giá trị định danh sự cố');
        }

        // checking
        // $incident = $this->incidentChecking($id);

        // if (!$incident) {
        //     return $this->sendError('Sự cố không tồn tại');
        // }




    }

    public function incidentChecking($id)
    {

    }

    public function taskListing(Request $request)
    {
        $type_id = $request->get('id');

        if(!$type_id) {
            return $this->sendError('Không có giá trị định danh nhóm sự cố', 400);
        }

        $page = $request->get('page');
        $limit = $request->get('limit');
        $metadata = [];

        if (!$page || !$limit) {
            $tasks = Task::where('type',$type_id)->get();
        } else {
            $tasks = Task::where('type',$type_id)->offset(($page - 1) * $limit)->limit($limit)->get();

            $count = Task::where('type',$type_id)->count();
            $total = ceil($count / $limit);

            $metadata = [
                'total' => (int) $total,
                'page' => (int) $page,
                'limit' => (int) $limit
            ];
        }

        $data[] = [
            'metadata' => $metadata,
            'tasks' => $tasks
        ];

        return $this->sendResponse($data);
    }
}
