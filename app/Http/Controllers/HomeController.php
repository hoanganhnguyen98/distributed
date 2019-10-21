<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use session;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | General functions for website
    |
    */

	/**
     * Show homepage.
     *
     * @return void
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Switch language and return back.
     *
     * @param $locale
     * @return void
     */
    public function lang($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
