<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillController extends Controller
{
    /**
     * Show create bill form.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showCreateBillForm()
    {
        return view('user.receptionist.bill.create-bill');
    }
}
