<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Table;
use Auth;
use App;
use session;

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
     * Switch language and return back.
     *
     * @param $locale
     * @return \Illuminate\Http\Response
     */
    public function lang($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
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
            // show receptionist role homepage
            return $this->getReceptionistHome($area);
        } else if ($role == 'waiter') {
            // show waiter role homepage
            return $this->getWaiterHome($area);
        } else {
            return view('home');
        }
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
        $table2s = Table::where([['area', $area], ['size', 2]])->get();
        $table4s = Table::where([['area', $area], ['size', 4]])->get();
        $table10s = Table::where([['area', $area], ['size', 10]])->get();

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
        $prepare = 0;
        $run = 0;
        foreach ($table2s as $table2) {
            if ($table2->status == 'ready') {
                $ready = $ready + 1;
            } else if ($table2->status == 'prepare') {
                $prepare = $prepare + 1;
            } else if ($table2->status == 'run') {
                $run = $run + 1;
            }
        }

        foreach ($table4s as $table4) {
            if ($table4->status == 'ready') {
                $ready = $ready + 1;
            } else if ($table4->status == 'prepare') {
                $prepare = $prepare + 1;
            } else if ($table4->status == 'run') {
                $run = $run + 1;
            }
        }

        foreach ($table10s as $table10) {
            if ($table10->status == 'ready') {
                $ready = $ready + 1;
            } else if ($table10->status == 'prepare') {
                $prepare = $prepare + 1;
            } else if ($table10->status == 'run') {
                $run = $run + 1;
            }
        }

        return view('user.receptionist.home.home',
            compact('area', 'table2s', 'table4s', 'table10_1s', 'table10_2s', 'ready', 'prepare', 'run'));
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
        $table2s = Table::where([['area', $area], ['size', 2]])->get();
        $table4s = Table::where([['area', $area], ['size', 4]])->get();
        $table10s = Table::where([['area', $area], ['size', 10]])->get();

        return view('user.waiter.home.home', compact('area', 'table2s', 'table4s', 'table10s'));
    }
}
