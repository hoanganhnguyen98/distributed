<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SendMailAfterCreate;
use App\Notifications\ForgetPasswordApp;
use App\Http\Requests\AccountRequest;
use Validator;
use Cloudder;
use File;

class UserController extends BaseController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['user_id'] =  $user->user_id;
            $success['name'] =  $user->name;
            $success['phone'] = $user->phone;
            $success['address'] = $user->address;
            $success['image'] = $user->image;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    /**
     * Create a new account.
     *
     * @param App\Http\Requests\AccountRequest $request
     * @return \Illuminate\Http\Response
     */
    protected function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'name' => 'required',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => 'required',
                'address' => 'required',
                'password' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }

            // create path to store in cloud
            $public_id = "ninja_restaurant/accounts/".(explode('@', $request->email)[0]);
            // upload to cloud
            Cloudder::upload("https://res.cloudinary.com/ninjahh/image/upload/v1587546081/ninja_restaurant/accounts/test1.jpg", $public_id);
            // get url of image
            $resize = array("width" => 300, "height" => 300, "crop" => "fill");
            $img_url = Cloudder::show($public_id, $resize);

            // create new account
            $user = User::create([
                'user_id' => explode('@', $request->email)[0],
                'area' => 'shuriken',
                'role' => 'user',
                'name' => $request->name,
                'image' => $img_url,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // send a mail with password to user email
            // $user->notify(new SendMailAfterCreate($request->password));

            try {
                $user->notify(new SendMailAfterCreate($request->password));
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->sendError('Validation Error.', $e);
            }

            DB::commit();
            return $this->sendResponse('Registed', 'Register successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError('Validation Error.', $e);
        }
    }

    /**
     * Edit account information.
     *
     * @param App\Http\Requests\AccountRequest $request
     * @return \Illuminate\Http\Response
     */
    protected function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $user = User::where('email', $request->email)->first();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();
   
        return $this->sendResponse('Updated', 'Updated successfully.');
    }

    protected function changePassword(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'new_password' => 'required',
            'old_password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $user = User::where('user_id', $request->user_id)->first();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = bcrypt($request->new_password);
            $user->save();

            return $this->sendResponse('Changed', 'Changed password successfully.');
        } else {
            return $this->sendError('Incorrect old password', 'Incorrect old password');
        }
    }

    protected function forgetPassword(Request $request)
    {
        $rules = [
            'email' =>  'required|email'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if ($user !== null) {
            $checkCode = Str::random(8);
            $user->remember_token = $checkCode;
            $user->save();

            $success['checkCode'] = $checkCode;
            $success['email'] = $request->email;

            // send mail
            $user->notify(new ForgetPasswordApp($checkCode));

            return $this->sendResponse($success, 'Check email to get code!');
        } else {
            return $this->sendError('Incorrect email', 'Incorrect email');
        }
    }

    protected function resetPassword(Request $request)
    {
        $rules = [
            'email' =>  'required|email',
            'new_password' => 'required',
            'check_code' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::where([['email', $request->email], ['remember_token', $request->check_code]])->first();
        
        if ($user !== null) {
            $user->password = bcrypt($request->new_password);
            $user->save();

            return $this->sendResponse('Reseted', 'Reset password successfully!');
        } else {
            return $this->sendError('Fail', 'Fail');
        }
    }
}
