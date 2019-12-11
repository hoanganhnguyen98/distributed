<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Validator;
use Auth;
use App\Model\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     *
     *
     * @var string
     */
    private $email;

    /**
     *
     *
     * @var string
     */
    private $password;

    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->email = $request->email;
        $this->password = $request->password;
    }

    /**
     * Show login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Check input to login.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        // redirect back with errors if input is not validated
        $validated = $request->validated();

        return
        $this->isValidAccount() ?
            ($this->isNotFirstLogin() ? $this->getHome() : $this->getFirstLoginForm())
        : $this->invalidAccountErrors();
    }

    /**
     * Check if account is valid.
     *
     * @return bool
     */
    private function isValidAccount()
    {
        return Auth::validate(['email' => $this->email, 'password' => $this->password]);
    }

    /**
     * Check if account is not first time to login.
     *
     * @return bool
     */
    private function isNotFirstLogin()
    {
        return Auth::attempt(['email' => $this->email, 'password' => $this->password, 'first_login' => 1]);
    }

    /**
     * Return homepage.
     *
     * @return \Illuminate\Http\Response
     */
    private function getHome()
    {
        return redirect()->route('home');
    }

    /**
     * Return first login form.
     *
     * @return \Illuminate\Http\Response
     */
    private function getFirstLoginForm()
    {
        return view('auth.first-login')->with('email', $this->email);
    }

    /**
     * Return errors when account is invalid.
     *
     * @return \Illuminate\Http\Response
     */
    private function invalidAccountErrors()
    {
        return redirect()->back()->withErrors(Lang::get('notify.errors.login'))->withInput();
    }

    /**
     * Logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
       Auth::logout();
       return redirect()->route('home');
    }
}
