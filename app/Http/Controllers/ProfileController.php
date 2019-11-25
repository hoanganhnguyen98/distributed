<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use App\Model\User;

class ProfileController extends Controller
{
    /**
     * Show rpofile.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showProfile()
    {
    	$user = Auth::user();
        return view('user.settings.profile.profile', compact('user'));
    }

    /**
     * Show rpofile.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showChangePasswordForm()
    {
        $email = Auth::user()->email;
        return view('user.settings.profile.change-password', compact('email'));
    }

    /**
     * Change password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function changePassword(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'email' => ['required', 'string', 'max:255'],
                'old_password' => ['required', 'string', 'min:8'],
                'new_password' => ['required', 'string', 'min:8'],
                'repassword' => ['required', 'string', 'min:8', 'same:new_password'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // find account with email
            $user = User::where('email', $request->email)->first();

            // check if current password is incorrect
            if (!Hash::check($request->old_password, $user->password)) {
                $errors = Lang::get('notify.errors.change_password');
                return redirect()->back()->withErrors($errors)->withInput();
            }

            // save new password
            $user->password = Hash::make($request->new_password);
            $user->save();

            DB::commit();

            $success = Lang::get('notify.success.change_password');
            return redirect()->back()->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }
}
