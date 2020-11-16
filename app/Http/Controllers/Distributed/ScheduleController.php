<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Schedule;
use Carbon\Carbon;

class ScheduleController extends BaseController
{
    public function detail(Request $request)
    {
        $day = $request->get('day');
        $month = $request->get('month');
        $year = $request->get('year');

        if (!$day || !$month || !$year) {
            return $this->sendError('Cần có đủ 3 trường ngày, tháng, năm', 400);
        }

        $schedule = Schedule::where([['day' => $day], ['month' => $month], ['year' => $year]])->first();

        $data = [];
        if ($schedule) {
            $employee_ids = $schedule->employee_ids;

            if (strlen($employee_ids) > 1) {
                $employees = array_slice(explode(',', $employee_ids), 1, -1);

                foreach ($employees as $key => $employee) {
                    $data[] = [
                        'id' => $employee->id,
                        'employee_id' => $employee->$employee_id,
                        'name' => $employee->name
                    ];
                }
            }
        }

        return $this->sendResponse($data);
    }
}
