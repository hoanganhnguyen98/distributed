<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Employee;
use App\Model\Schedule;
use Carbon\Carbon;

class GenerateController extends BaseController
{
    public function generateEmployee(Request $request)
    {
        if ((int) $request->get('ninja') !== 133) {
            return $this->sendError('Không phải chỗ chơi, đi ra ngoài', 400);
        }

        $first = ['Nguyễn ', 'Trần ', 'Lê ', 'Ngô ', 'Trịnh ', 'Hoàng ', 'Cao ', 'Phan '];
        $mid = ['Văn ', 'Như ', 'Minh ', 'Quang '];
        $last = ['Đạt', 'Hảo', 'Tâm', 'Linh', 'Ninh', 'Anh', 'Hùng', 'Kiều', 'Thành'];

        $type = ['DE_DIEU', 'LUOI_DIEN', 'CHAY_RUNG', 'CAY_TRONG'];

        $number = $request->get('number');

        for ($i=0; $i < $number; $i++) {
            $name = $first[array_rand($first)].$mid[array_rand($mid)].$last[array_rand($last)];
            $employee_id = (int) (rand(10, 99).$i.rand(0,9));

            Employee::create([
                'employee_id' => $employee_id,
                'name' => $name,
                'role' => 'INCIDENT_STAFF',
                'type' => $type[rand(0,3)],
                'current_id' => null,
                'pending_ids' => ',',
                'is_captain' => false,
                'all_ids' => ','
            ]);
        }

        return $this->sendResponse([]);
    }

    public function generateSchedule()
    {
        for ($i=1; $i < 31; $i++) {
            $employees = Employee::inRandomOrder()->limit(rand(5,8))->get();

            $employee_ids = ',';
            foreach ($employees as $key => $employee) {
                $employee_ids .= $employee->employee_id . ',';
            }

            $schedule = Schedule::where([['day', $i], ['month', 11], ['year', 2020]])->first();

            if ($schedule) {
                $schedule->employee_ids =  $employee_ids;
                $schedule->save();
            } else {
                Schedule::create([
                    'employee_ids' => $employee_ids,
                    'day' => $i,
                    'month' => 11,
                    'year' => 2020
                ]);
            }
        }

        for ($i=1; $i < 32; $i++) {
            $employees = Employee::inRandomOrder()->limit(rand(10,15))->get();

            $employee_ids = ',';
            foreach ($employees as $key => $employee) {
                $employee_ids .= $employee->employee_id . ',';
            }

            $schedule = Schedule::where([['day', $i], ['month', 12], ['year', 2020]])->first();

            if ($schedule) {
                $schedule->employee_ids =  $employee_ids;
                $schedule->save();
            } else {
                Schedule::create([
                    'employee_ids' => $employee_ids,
                    'day' => $i,
                    'month' => 12,
                    'year' => 2020
                ]);
            }
        }

        return $this->sendResponse([]);
    }
}
