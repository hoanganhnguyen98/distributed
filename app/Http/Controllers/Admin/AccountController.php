<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Model\User;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendMailAfterCreate;
use Cloudder;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        
    }

    /**
     * Show create account form.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showCreateAccountForm()
    {
        if (Auth::user()->role == 'admin') {
            return view('user.admin.account.create-account');
        }

        return view('404');
    }

    /**
     * Create a new account.
     *
     * @param App\Http\Requests\AccountRequest $request
     * @return \Illuminate\Http\Response
     */
    protected function createAccount(AccountRequest $request)
    {
        // redirect back with errors if input is not validated
        $validated = $request->validated();

        // get image to store in cloud
        if ($request->hasFile('image')){
            // create path to store in cloud
            $public_id = "ninja_restaurant/accounts/".(explode('@', $request->email)[0]);
            // upload to cloud
            Cloudder::upload($request->file('image'), $public_id);
            // get url of image
            $resize = array("width" => 300, "height" => 300, "crop" => "fill");
            $img_url = Cloudder::show($public_id, $resize);
        }

        $password = Str::random(8);

        // create new account
        $user = User::create([
            'user_id' => explode('@', $request->email)[0],
            'area' => $request->area,
            'role' => $request->role,
            'name' => $request->name,
            'image' => $img_url,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);

        // send a mail with password to user email
        $user->notify(new SendMailAfterCreate($password));

        $success = Lang::get('notify.success.create-account');
        return redirect()->back()->with('success', $success);
    }

    /**
     * Show account list.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showAccountList()
    {
        if (Auth::user()->role == 'admin') {
            $accounts = User::all();
            return view('user.admin.account.account-list', compact('accounts'));
        }

        return view('404');
    }

    /**
     * Show account detail.
     *
     * @param $user_id id of account
     * @return \Illuminate\Http\Response
     */
    protected function showAccountDetail($user_id)
    {
        if (Auth::user()->role == 'admin') {
            $account = User::where('user_id', $user_id)->first();
            return view('user.admin.account.account-detail.account-detail', compact('account'));
        }

        return view('404');
    }

    /**
     * Change image.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function changeImage(Request $request)
    {
       try {
            DB::beginTransaction();

            $rules = [
                'user_id' => ['required'],
                'image' => ['required'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // get image to store in cloud
            if ($request->hasFile('image')){
                // create path to store in cloud
                $public_id = "ninja_restaurant/accounts/".($request->user_id);
                // upload to cloud
                Cloudder::upload($request->file('image'), $public_id);
                // get url of image
                $resize = array("width" => 300, "height" => 300, "crop" => "fill");
                $img_url = Cloudder::show($public_id, $resize);
            }

            // save new image
            $user = User::where('user_id', $request->user_id)->first();
            $user->image = $img_url;
            $user->save();

            DB::commit();

            $success = Lang::get('notify.success.change-image-account');
            return redirect()->back()->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        } 
    }

    /**
     * Edit account information.
     *
     * @param App\Http\Requests\AccountRequest $request
     * @return \Illuminate\Http\Response
     */
    protected function editAccount(AccountRequest $request)
    {
        try {
            DB::beginTransaction();

            // redirect back with errors if input is not validated
            $validated = $request->validated();

            // update new account information
            $user = User::where('user_id', $request->user_id)->first();
            $user->area = $request->area;
            $user->role = $request->role;
            $user->name = $request->name;
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->save();

            DB::commit();

            $success = Lang::get('notify.success.edit-account');
            return redirect()->back()->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }

    /**
     * Delete account.
     *
     * @param $user_id id of account
     * @return \Illuminate\Http\Response
     */
    protected function deleteAccount($user_id)
    {
        try {
            DB::beginTransaction();

            $account = User::where('user_id', $user_id)->first();
            $account->delete();

            DB::commit();

            $success = Lang::get('notify.success.delete-account');
            return redirect()->route('account-list')->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }
}
