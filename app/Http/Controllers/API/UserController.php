<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends BaseController
{
    public function index()
    {
        $success['token'] = 1;
        $success['name'] = "ninja";

        return $this->sendResponse($success, 'OK');
    }

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
