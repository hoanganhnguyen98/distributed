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



        // return $this->sendResponse('Removed', 'Remove food successfully.');
    }

    public function incidentChecking($id)
    {

    }

    public function taskListing(Request $request)
    {
        $type_id = $request->get('id');

        if(!$type_id) {
            return $this->sendError('Không có giá trị định danh nhóm sự cố', 'Lưới điện: 000000, Đê điều: 111111, Cháy rừng: 222222, Cây trồng: 333333');
        }


        $tasks = Task::where('type',$type_id)->get();

        return $this->sendResponse($tasks);
    }
}
