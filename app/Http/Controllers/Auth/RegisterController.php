<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Show register form.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showRegistrationForm()
    {
        return view('user.admin.function.create');
    }

    /**
     * Check input to create a new user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function register(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'regex:/(0)[0-9]{9}/'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if ($request->hasFile('image')) {
                $image = $request->image;
                $avatar = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('\img\avatar');
                $image->move($destinationPath, $avatar);
            }

            // generate a random password
            $password = Str::random(8);
            dd($request->all()); exit();
            User::create([
                'user_id' => explode('@', $request->email)[0],
                'area_id' => $request->area_id,
                'role' => $request->role,
                'name' => $request->name,
                'image' => $avatar,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => bcrypt($password),
            ]);

            DB::commit();

            $success = Lang::get('notify.success.register');
            return redirect()->back()->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }
}
