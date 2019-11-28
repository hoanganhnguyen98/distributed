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
        // $this->middleware('auth');
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
        if ($role == 'receptionist') {
            $area = Auth::user()->area;

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

            return view('user.receptionist.home.home', compact('area', 'table2s', 'table4s', 'table10_1s', 'table10_2s'));
        } else {
            return view('home');
        }
    }
}
