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
            // $success['token'] =  123;
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
        Cloudder::upload(File::get(asset('/img/avt.jpg')), $public_id);
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
        $user->notify(new SendMailAfterCreate($request->password));

        return $this->sendResponse('Registed', 'Register successfully.');
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
}
