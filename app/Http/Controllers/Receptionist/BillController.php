<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use App\Model\Table;
use App\Model\Bill;
use Auth;

class BillController extends Controller
{
    /**
     * Show create bill form.
     *
     * @param $table_id
     * @return \Illuminate\Http\Response
     */
    protected function showCreateBillForm($table_id)
    {
        if (Auth::user()->role == 'receptionist') {
            // update status for table
            $new_status = 'prepare';
            $table = Table::where('table_id', $table_id)->first();
            $table->status = $new_status;
            $table->save();

            return view('user.receptionist.bill.create-bill', compact('table_id'));
        } else {
            return view('404');
        }
    }

    /**
     * Cancel create new bill.
     *
     * @param $table_id
     * @return \Illuminate\Http\Response
     */
    protected function cancelCreateBill($table_id)
    {
        if (Auth::user()->role == 'receptionist') {
            // update status for table
            $new_status = 'ready';
            $table = Table::where('table_id', $table_id)->first();
            $table->status = $new_status;
            $table->save();
            return redirect()->route('home');
        } else {
            return view('404');
        }
    }

    /**
     * Cancel create new bill.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function createBill(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'table_id' => ['required'],
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'regex:/(0)[0-9]{9}/'],
                'city' => ['required', 'string', 'max:255'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // update status for table
            $new_status = 'run';
            $table = Table::where('table_id', $request->table_id)->first();
            $table->status = $new_status;
            $table->save();

            Bill::create([
                'receptionist_id' => Auth::user()->user_id,
                'table_id' => $request->table_id.'-'.Auth::user()->area,
                'customer_name' => $request->name,
                'street' => $request->street,
                'district' => $request->district,
                'city' => $request->city,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            DB::commit();

            $success = Lang::get('notify.success.create-bill');
            return redirect()->route('home')->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }

    /**
     * Get bill list.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showBillList()
    {
        if (Auth::user()->role == 'receptionist') {
            $area = Auth::user()->area;
            $today = date('Y-m-d');
            $today_bills = Bill::whereDate('created_at', $today);

            $bills = $today_bills->sortable('id')->paginate(10);

            return view('user.receptionist.bill.bill-list', compact('bills', 'area'));
        } else {
            return view('404');
        }
    }
}
