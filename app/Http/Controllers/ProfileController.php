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
     * User to change password.
     *
     * @var string[]
     */
    private $user;

    /**
     * String to replace the old password.
     *
     * @var string
     */
    private $newPassword;

    /**
     * String to matches with current password.
     *
     * @var string
     */
    private $oldPassword;

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
            $this->user = User::where('email', $request->email)->first();
            $this->newPassword = $request->new_password;
            $this->oldPassword = $request->old_password;

            $this->isOldPassword() ? $this->saveNewPassword() : $this->getErrorPassword();

            DB::commit();

            $success = Lang::get('notify.success.change_password');
            return redirect()->back()->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }

    /**
     * Save new password.
     *
     * @return void
     */
    private function saveNewPassword()
    {
        $this->user->password = Hash::make($this->newPassword);
        $this->user->save();
    }

    /**
     * Verify that the old password matches the current password.
     *
     * @return bool
     */
    private function isOldPassword()
    {
        return Hash::check($this->oldPassword, $this->user->password);
    }

    /**
     * Return errors when old password is not same.
     *
     * @return \Illuminate\Http\Response
     */
    private function getErrorPassword()
    {
        $errors = Lang::get('notify.errors.change_password');
        return redirect()->back()->withErrors($errors)->withInput();
    }
}
