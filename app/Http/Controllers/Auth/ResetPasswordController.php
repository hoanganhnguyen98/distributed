<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\PasswordReset;
use App\Model\User;
use Validator;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    /**
     * Show form to reset password.
     *
     * @param $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm($token = null)
    {
        $email = PasswordReset::where('token', explode('=', $token)[1])->first()->email;
        return view('auth.passwords.reset')->with('email' , $email);
    }
     
     /**
     * Check to create new password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'password' => ['required', 'string', 'min:8'],
                'repassword' => ['required', 'string', 'min:8', 'same:password'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $email = $request->email;
            $password = $request->password;
            $user = User::where('email', $email)->first();
            $user->password = bcrypt($password);
            $user->first_login = 1;
            $user->save();

            DB::commit();

            $success = Lang::get('notify.success.reset');
            return redirect()->route('login')->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
