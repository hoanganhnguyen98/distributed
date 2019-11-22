<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Model\User;
use App\Model\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;
use App\Notifications\ResetPasswordRequest;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show form to enter email to send request.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a mail with a link to reset password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $rules = [
            'email' =>  'required|email'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user != null) {
            $passwordReset = PasswordReset::updateOrCreate([
                'email' => $user->email,
            ], [
                'token' => Str::random(60),
            ]);
            // send mail
            $user->notify(new ResetPasswordRequest($passwordReset->token));

            $success = Lang::get('notify.success.reset_email');
            return redirect()->back()->with('success', $success);
        } else {
            $errors = Lang::get('notify.errors.reset');
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }
}
