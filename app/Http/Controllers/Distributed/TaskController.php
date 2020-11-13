<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Food;
use App\Model\Bill;
use App\Model\BillDetail;
use App\Http\Resources\CurrentCart as CurrentCart;
use App\Http\Resources\CartHistory as CartHistory;
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
}
