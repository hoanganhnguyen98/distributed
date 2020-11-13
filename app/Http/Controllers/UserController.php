<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use session;
use App\Model\Food;
use App\Model\Type;
use App\Model\Source;

class UserController extends Controller
{
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
     * Show main hompage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	$foods = Food::all();
        $types = Type::all();
        $sources = Source::all();
        return view('home.home', compact('foods', 'types', 'sources'));
    }
}
