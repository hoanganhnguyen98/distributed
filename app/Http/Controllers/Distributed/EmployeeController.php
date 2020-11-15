<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Employee;
use Carbon\Carbon;

class EmployeeController extends BaseController
{
    public function listing(Request $request)
    {
    }

    public function detail(Request $request)
    {
        Employee::create([
            'employee_id' => 100,
            'name' => 'Nguyễn Văn X',
            'current_id' => 1000,
            'pending_ids' => ','
        ]);
    }
}
