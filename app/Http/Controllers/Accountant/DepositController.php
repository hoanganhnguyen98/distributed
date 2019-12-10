<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use App\Model\Deposit;
use App\Model\User;
use Auth;

class DepositController extends Controller
{
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
     *
     *
     * @var integer
     */
    private $vnd;

    /**
     *
     *
     * @var integer
     */
    private $usd;

    /**
     * Create new deposit for receptionist.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function createDeposit(Request $request)
    {
        $rules = [
            'vnd' => ['required', 'numeric'],
            'usd' => ['required', 'numeric'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->email = $request->email;
        $this->password = $request->password;
        $this->vnd = $request->vnd;
        $this->usd = $request->usd;

        return $this->isValidAccount() ? $this->isValidRole() : $this->isInvalidAccount();
    }

    /**
     * Create new deposit for receptionist.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showDepositList()
    {
        $today = date('Y-m-d');
        $deposits = Deposit::whereDate('created_at', $today)->where('status', 'new')->get();

        return view('user.accountant.deposit-list', compact('deposits'));
    }

    /**
     * Check if account role is receptionist.
     *
     * @return bool
     */
    private function isReceptionistRole()
    {
        return User::where('email', $this->email)->first()->role == 'receptionist';
    }

    /**
     * Check if account exists.
     *
     * @return bool
     */
    private function isValidAccount()
    {
        return Auth::attempt(['email' => $this->email, 'password' => $this->password]);
    }

    /**
     * Return if account role is valid.
     *
     * @return \Illuminate\Http\Response
     */
    private function isValidRole()
    {
        return $this->isReceptionistRole() ? $this->createNewDeposit() : $this->isInvalidRole();
    }

    /**
     * Create new deposit and return.
     *
     * @return \Illuminate\Http\Response
     */
    private function createNewDeposit()
    {
        Deposit::create([
            'user_id' => explode('@', $this->email)[0],
            'vnd' => $this->vnd,
            'usd' => $this->usd,
        ]);

        $success = Lang::get('notify.success.create-deposit');
        return redirect()->back()->with('success', $success);
    }

    /**
     * Return errors when account role is invalid.
     *
     * @return \Illuminate\Http\Response
     */
    private function isInvalidRole()
    {
        $errors = Lang::get('notify.errors.create-deposit-role');
        return redirect()->back()->withErrors($errors)->withInput();
    }

    /**
     * Return errors when account is invalid.
     *
     * @return \Illuminate\Http\Response
     */
    private function isInvalidAccount()
    {
        $errors = Lang::get('notify.errors.create-deposit-account');
        return redirect()->back()->withErrors($errors)->withInput();
    }
}
