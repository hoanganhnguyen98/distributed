<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Table;
use App\Model\BillDetail;
use App\Model\Bill;
use App\Model\Food;
use App\Model\Deposit;
use Auth;
use Illuminate\Support\Facades\Lang;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // check user role and area to show homepage
        $role = Auth::user()->role;
        $area = Auth::user()->area;

        if ($role == 'receptionist') {
            $user_id = Auth::user()->user_id;
            if ($this->gettedDeposit($user_id)) {
                // show receptionist role homepage
                return $this->getReceptionistHome($area);
            } else {
                Auth::logout();
                return redirect()->back()->withErrors(Lang::get('notify.errors.login-deposit'))->withInput();
            }
        } else if ($role == 'waiter') {
            // show waiter role homepage
            return $this->getWaiterHome($area);
        } else if ($role == 'kitchen_manager') {
            // show kitchen manager role homepage
            return $this->getKitchenManagerHome($area);
        } else if ($role == 'accountant') {
            // show accountant role homepage
            return $this->getAccountantHome($area);
        } else if ($role == 'admin') {
            return $this->getAdminHome($area);
        }
    }

    protected function gettedDeposit($user_id)
    {
        $today = date('Y-m-d');
        $deposits = Deposit::whereDate('created_at', $today)->get();

        foreach ($deposits as $deposit) {
            if ($deposit->user_id == $user_id && $deposit->status == 'new') {
                return true;
            }
        }

        return false;
    }

    /**
     * Show homepage with admin role.
     *
     * @param $area
     * @return \Illuminate\Contracts\Support\Renderable
     */
    private function getAdminHome($area)
    {
        // get table list
        $table2s = Table::where([['area', $area], ['size', 2]])->orderBy('id')->get();
        $table4s = Table::where([['area', $area], ['size', 4]])->orderBy('id')->get();
        $table10s = Table::where([['area', $area], ['size', 10]])->orderBy('id')->get();

        // bisect the table10s array
        $table10_1s = array();
        $table10_2s = array();
        foreach ($table10s as $key => $value) {
            if ($key < 4) {
                $table10_1s[] = $value;
            } else {
                $table10_2s[] = $value;
            }
        }
        $ready = 0;
        $run = 0;
        foreach ($table2s as $table2) {
            if ($table2->status == 'ready') {
                $ready = $ready + 1;
            } else if ($table2->status == 'run') {
                $run = $run + 1;
            }
        }

        foreach ($table4s as $table4) {
            if ($table4->status == 'ready') {
                $ready = $ready + 1;
            } else if ($table4->status == 'run') {
                $run = $run + 1;
            }
        }

        foreach ($table10s as $table10) {
            if ($table10->status == 'ready') {
                $ready = $ready + 1;
            } else if ($table10->status == 'run') {
                $run = $run + 1;
            }
        }

        return view('user.admin.home.home',
            compact('area', 'table2s', 'table4s', 'table10_1s', 'table10_2s', 'ready', 'run'));
    }

    /**
     * Show homepage with receptionist role.
     *
     * @param $area
     * @return \Illuminate\Contracts\Support\Renderable
     */
    private function getReceptionistHome($area)
    {
        // get table list
        $table2s = Table::where([['area', $area], ['size', 2]])->orderBy('id')->get();
        $table4s = Table::where([['area', $area], ['size', 4]])->orderBy('id')->get();
        $table10s = Table::where([['area', $area], ['size', 10]])->orderBy('id')->get();

        // bisect the table10s array
        $table10_1s = array();
        $table10_2s = array();
        foreach ($table10s as $key => $value) {
            if ($key < 4) {
                $table10_1s[] = $value;
            } else {
                $table10_2s[] = $value;
            }
        }
        $ready = 0;
        $run = 0;
        foreach ($table2s as $table2) {
            if ($table2->status == 'ready') {
                $ready = $ready + 1;
            } else if ($table2->status == 'run') {
                $run = $run + 1;
            }
        }

        foreach ($table4s as $table4) {
            if ($table4->status == 'ready') {
                $ready = $ready + 1;
            } else if ($table4->status == 'run') {
                $run = $run + 1;
            }
        }

        foreach ($table10s as $table10) {
            if ($table10->status == 'ready') {
                $ready = $ready + 1;
            } else if ($table10->status == 'run') {
                $run = $run + 1;
            }
        }

        return view('user.receptionist.home.home',
            compact('area', 'table2s', 'table4s', 'table10_1s', 'table10_2s', 'ready', 'run'));
    }

    /**
     * Show homepage with waiter role.
     *
     * @param $area
     * @return \Illuminate\Contracts\Support\Renderable
     */
    private function getWaiterHome($area)
    {
        // get table list
        $tables = Table::where('area', $area)->orderBy('id')->get();

        return view('user.waiter.home.home', compact('area', 'tables'));
    }

    /**
     * Show homepage with kitchen manager role.
     *
     * @param $area
     * @return \Illuminate\Contracts\Support\Renderable
     */
    private function getKitchenManagerHome($area)
    {
        $today = date('Y-m-d');
        $current_orders = BillDetail::whereDate('created_at', $today)->get();

        // get all order in kitchen manager area
        $order_news = array();
        $order_prepares = array();
        foreach ($current_orders as $order) {
            if($order->status == 'new') {
                // get current table id of order with bill id
                $table_id = Bill::where('id', $order->bill_id)->first()->table_id;
                // get current area of bill
                $bill_area = explode('-', $table_id)[1];

                if ($bill_area == $area) {
                    $order_detail = array();
                    $order_detail['id'] = $order->id;
                    $order_detail['food_name'] = Food::where('id', $order->food_id)->first()->name;
                    $order_detail['number'] = $order->number;
                    $order_news[] = $order_detail;
                }
            } elseif ($order->status == 'prepare') {
                // get current table id of order with bill id
                $table_id = Bill::where('id', $order->bill_id)->first()->table_id;
                // get current area of bill
                $bill_area = explode('-', $table_id)[1];

                if ($bill_area == $area) {
                    $order_detail = array();
                    $order_detail['id'] = $order->id;
                    $order_detail['table'] = $table_id;
                    $order_detail['food_name'] = Food::where('id', $order->food_id)->first()->name;
                    $order_detail['number'] = $order->number;
                    $order_prepares[] = $order_detail;
                }
            }
        }

        return view('user.kitchen-manager.home.home', compact('order_news', 'order_prepares'));
    }

    /**
     * Show homepage with accountant role.
     *
     * @param $area
     * @return \Illuminate\Contracts\Support\Renderable
     */
    private function getAccountantHome($area)
    {
        $today = date('Y-m-d');
        $today_bills = Bill::whereDate('created_at', $today);

        $bills = $today_bills->sortable('id')->paginate(10);
        return view('user.accountant.home.home', compact('area', 'bills'));
    }
}
