<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Task;
use App\Model\Support;
use App\Model\Employee;
use Carbon\Carbon;
use App\Http\Controllers\Distributed\TaskController as TaskController;

class ExternalController extends BaseController
{
    public function reportListing()
    {
        $reports = $this->reportCounting();
        $supports = $this->supportCounting();
        $tasks = $this->taskCounting();
        $employees = $this->employeeCounting();

        $data = [
            'result_reports_total' => $reports,
            'support_requests_total' => $supports,
            'created_tasks_total' => $tasks,
            'joined_employee' => $employees
        ];

        return $this->sendResponse($data);
    }

    public function reportCounting()
    {
        $reports = Report::all()->count();
        $accepts = Report::where('status', 'accept')->get()->count();
        $rejects = Report::where('status', 'reject')->get()->count();
        $waitings = $reports - $accepts - $rejects;

        return [
            'label' => 'Báo cáo kết quả xử lý sự cố',
            'sent_total' => $reports,
            'accepted_total' => $accepts,
            'rejected_total' => $rejects,
            'waiting_total' => $waitings
        ];
    }

    public function supportCounting()
    {
        $supports = Support::all()->count();
        $accepts = Support::where('status', 'accept')->get()->count();
        $rejects = Support::where('status', 'reject')->get()->count();
        $waitings = $supports - $accepts - $rejects;

        return [
            'label' => 'Yêu cầu hỗ trợ xử lý sự cố',
            'sent_total' => $supports,
            'accepted_total' => $accepts,
            'rejected_total' => $rejects,
            'waiting_total' => $waitings
        ];
    }

    public function taskCounting()
    {
        $tasks = Task::all()->count();
        $doing = Task::where('status', 'doing')->get()->count();
        $done = Task::where('status', 'done')->get()->count();

        return [
            'label' => 'Công việc xử lý sự cố',
            'created_total' => $tasks,
            'doing_total' => $doing,
            'done_total' => $done,
        ];
    }

    public function employeeCounting()
    {
        $employees = Employee::all()->count();

        return [
            'label' => 'Nhân viên tham gia xử lý sự cố',
            'joined_total' => $employees
        ];
    }
}
