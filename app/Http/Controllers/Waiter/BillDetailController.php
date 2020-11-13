<?php

namespace App\Http\Controllers\Waiter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use App\Model\Food;
use App\Model\Bill;
use App\Model\BillDetail;
use Auth;
use App\Events\DislayBillDetailInKitchenManagerEvent;

class BillDetailController extends Controller
{
    /**
     * Show add new bill detail form.
     *
     * @param $table_id
     * @return \Illuminate\Http\Response
     */
    protected function showAddBillDetailForm($table_id)
    {
        if (Auth::user()->role == 'waiter') {
            // get all foods to show
            $foods = Food::all();
            // get bill in table to add bill detail
            $table = $table_id.'-'.Auth::user()->area;
            $bill = Bill::where([['table_id', $table],['status','!=', 'done']])->first();
            $bill_id = $bill->id;

            return view('user.waiter.create-bill-detail', compact('foods', 'bill_id'));
        } else {
            return view('404');
        }
    }

    /**
     * Add new bill detail.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function addBillDetail(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'bill_id' => ['required'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            for ($i = 0; $i < count($request->food_id); $i++) {
                BillDetail::create([
                    'bill_id' => $request->bill_id,
                    'status' => 'new',
                    'food_id' => $request->food_id[$i],
                    'number' => $request->amount[$i],
                ]);

                $food_name = Food::where('id', $request->food_id[$i])->first()->name;
                $order_id = BillDetail::where([['bill_id', $request->bill_id], ['status', 'new'], ['food_id', $request->food_id[$i]], ['number', $request->amount[$i]]])->first()->id;
                // push event to server Pusher to get in other screen
                event(new DislayBillDetailInKitchenManagerEvent($food_name ,$request->amount[$i], $order_id));
            }

            DB::commit();

            $success = Lang::get('notify.success.create-bill-detail');
            return redirect()->route('home')->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }
}
