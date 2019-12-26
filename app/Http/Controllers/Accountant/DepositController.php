<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DepositRequest;
use Illuminate\Support\Facades\Lang;
use App\Model\Deposit;
use App\Model\User;
use Auth;

class DepositController extends Controller
{
    /**
     * Email value, string to determine account.
     *
     * @var string
     */
    private $email;

    /**
     * Password value, string to determine account.
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
     *
     *
     * @var string[]
     */
    private $deposit;

    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->email = $request->email;
        $this->password = $request->password;
        $this->vnd = $request->vnd;
        $this->usd = $request->usd;
        // get deposit of account
        $this->deposit = Deposit::whereDate('created_at', date('Y-m-d'))->where('user_id', explode('@', $this->email)[0])->first();
    }

    /**
     * Show deposit list.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showDepositList()
    {
        $deposits = Deposit::where('status', 'new')->get();

        return view('user.accountant.deposit-list', compact('deposits'));
    }

    /**
     * Show form to create new deposit.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showCreateDepositForm()
    {
        if (Auth::user()->role == 'accountant') {
            return view('user.accountant.deposit.create-deposit');
        }

        return view('404');
    }

    /**
     * Show form to repay deposit.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showRepayDepositForm()
    {
        if (Auth::user()->role == 'accountant') {
            return view('user.accountant.deposit.repay-deposit');
        }

        return view('404');
    }

    /**
     * Create new deposit for receptionist.
     *
     * @param App\Http\Requests\DepositRequest $request
     * @return \Illuminate\Http\Response
     */
    protected function create(DepositRequest $request)
    {
        // redirect back with errors if input is not validated
        $validated = $request->validated();

        return
        $this->isValidAccount() ?
            ($this->isReceptionistRole() ?
                ($this->isNulltDeposit() ? $this->createNewDeposit() : $this->ExistedDepositErrors())
            : $this->invalidRoleErrors())
        : $this->invalidAccountErrors();
    }

    /**
     * Confirm to repay deposit of receptionist.
     *
     * @param App\Http\Requests\DepositRequest $request
     * @return \Illuminate\Http\Response
     */
    protected function repay(DepositRequest $request)
    {
        // redirect back with errors if input is not validated
        $validated = $request->validated();

        return
        $this->isValidAccount() ?
            ($this->isReceptionistRole() ?
                (!$this->isNulltDeposit() ?
                    ($this->isValidStatus() ?
                        ($this->isCorrectDeposit() ? $this->confirmDeposit() : $this->incorrectDepositErrors())
                    : $this->invalidStatusErrors())
                : $this->NullDepositErrors())
            : $this->invalidRoleErrors())
        : $this->invalidAccountErrors();
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

        return redirect()->back()->with('success', Lang::get('notify.success.create-deposit'));
    }

    /**
     * Create new deposit and return.
     *
     * @return \Illuminate\Http\Response
     */
    private function confirmDeposit()
    {
        $this->deposit->status = 'done';
        $this->deposit->save();

        return redirect()->back()->with('success', Lang::get('notify.success.repay-deposit'));
    }

    /**
     * Check if account exists.
     *
     * @return bool
     */
    private function isValidAccount()
    {
        return Auth::validate(['email' => $this->email, 'password' => $this->password]);
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
     * Check if deposit is null.
     *
     * @return bool
     */
    private function isNulltDeposit()
    {
        return $this->deposit == null;
    }

    /**
     * Check if deposit matches with input.
     *
     * @return bool
     */
    private function isCorrectDeposit()
    {
        return $this->deposit->vnd == $this->vnd && $this->deposit->usd == $this->usd;
    }

    /**
     * Check if deposit status is new.
     *
     * @return bool
     */
    private function isValidStatus()
    {
        return $this->deposit->status == 'new';
    }

    /**
     * Return errors when account is invalid.
     *
     * @return \Illuminate\Http\Response
     */
    private function invalidAccountErrors()
    {
        return redirect()->back()->withErrors(Lang::get('notify.errors.deposit.invalidAccount'))->withInput();
    }

    /**
     * Return errors when account role is invalid.
     *
     * @return \Illuminate\Http\Response
     */
    private function invalidRoleErrors()
    {
        return redirect()->back()->withErrors(Lang::get('notify.errors.deposit.invalidRole'))->withInput();
    }

    /**
     * Return errors when deposit of account has exist.
     *
     * @return \Illuminate\Http\Response
     */
    private function ExistedDepositErrors()
    {
        return redirect()->back()->withErrors(Lang::get('notify.errors.deposit.existed'))->withInput();
    }

    /**
     * Return errors when deposit of account is null.
     *
     * @return \Illuminate\Http\Response
     */
    private function NullDepositErrors()
    {
        return redirect()->back()->withErrors(Lang::get('notify.errors.deposit.null'))->withInput();
    }

    /**
     * Return errors when deposit status is done.
     *
     * @return \Illuminate\Http\Response
     */
    private function invalidStatusErrors()
    {
        return redirect()->back()->withErrors(Lang::get('notify.errors.deposit.invalidStatus'))->withInput();
    }

    /**
     * Return errors when deposit does not match.
     *
     * @return \Illuminate\Http\Response
     */
    private function incorrectDepositErrors()
    {
        return redirect()->back()->withErrors(Lang::get('notify.errors.deposit.incorrect'))->withInput();
    }
}
