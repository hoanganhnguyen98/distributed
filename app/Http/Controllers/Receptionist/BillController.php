<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use App\Model\Table;
use App\Model\Bill;
use App\Model\BillDetail;
use App\Model\Food;
use App\Model\Deposit;
use Auth;
use PDF;
use App\Events\DisplayBillingTableInWaiterEvent;
use Cloudder;

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

            // push event to server Pusher to get in other screen
            event(new DisplayBillingTableInWaiterEvent($table_id ,$new_status));

            return redirect()->route('home');
        } else {
            return view('404');
        }
    }

    /**
     * Create new bill.
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

            // push event to server Pusher to get in other screen
            event(new DisplayBillingTableInWaiterEvent($request->table_id ,$new_status));

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

    /**
     * Edit bill information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function editBillForm(Request $request)
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

            $bill = Bill::where([['table_id', $request->table_id], ['status', 'new']])->first();

            $bill->customer_name = $request->name;
            $bill->street = $request->street;
            $bill->district = $request->district;
            $bill->city = $request->city;
            $bill->phone = $request->phone;
            $bill->email = $request->email;
            $bill->save();

            DB::commit();

            $success = Lang::get('notify.success.edit-bill');
            return redirect()->back()->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }

    /**
     * Show pay bill form.
     *
     * @param $table_id
     * @return \Illuminate\Http\Response
     */
    protected function showPayBillForm($table_id)
    {
        if (Auth::user()->role == 'receptionist') {
            // get table id to get current bill in this table
            $table_id = $table_id.'-'.Auth::user()->area;
            $bill = Bill::where([['table_id', $table_id], ['status', 'new']])->first();
            
            $output = $this->getBillDetail($bill);

            $bill_details = $output[0];
            $vndPrice  = $output[1];
            $usdPrice  = $output[2];

            return view('user.receptionist.bill.pay-bill.pay-bill',
                compact('bill', 'bill_details', 'vndPrice', 'usdPrice'));
        } else {
            return view('404');
        }
    }

    /**
     * Pay bill.
     *
     * @param $table_id, $type
     * @return \Illuminate\Http\Response
     */
    protected function payBill($table_id, $type)
    {
        if (Auth::user()->role == 'receptionist') {
            try {
                DB::beginTransaction();

                $bill = Bill::where([['table_id', $table_id], ['status', 'new']])->first();
                //get deposit of reception
                $deposit = Deposit::where([['user_id', Auth::user()->user_id], ['status', 'new']])->first();

                $output = $this->getBillDetail($bill);

                $bill_details = $output[0];
                $vndPrice  = $output[1];
                $usdPrice  = $output[2];

                // export a pdf as a invoice of customer
                $user = Auth::user(); // get information of receptionist
                $now = date("Y-m-d H:i:s"); // get current time
                $path = 'C:\Users\admin\Desktop\invoice-ninjarestaurant'.$bill->id.'.pdf';
                $mpdf = new \Mpdf\Mpdf();

                if ($type == 'vnd') {
                    $mpdf->WriteHTML(\View::make('user.receptionist.bill.pay-bill.vnd-invoice',
                        compact('user', 'bill', 'now', 'bill_details', 'vndPrice')));
                } elseif ($type == 'usd') {
                    $mpdf->WriteHTML(\View::make('user.receptionist.bill.pay-bill.usd-invoice',
                        compact('user', 'bill', 'now', 'bill_details', 'usdPrice')));
                }

                $mpdf->debug = true;
                // auto save file to path and return
                $mpdf->Output($path, 'F');

                // create path to store pdf in cloud
                $public_id = "ninja_restaurant/invoices/".$bill->id;
                // upload to cloud
                Cloudder::upload($path, $public_id);

                // update bill
                if ($type == 'vnd') {
                    $bill->total_price = $vndPrice;

                    //updaate deposit of receptionist
                    $deposit->vnd = $deposit->vnd + $vndPrice;
                } elseif ($type == 'usd') {
                    $bill->total_price = $usdPrice;

                    //updaate deposit of receptionist
                    $deposit->usd = $deposit->usd + $usdPrice;
                }

                $bill->status = 'done'; // status of bill, new -> done
                $bill->save();
                $deposit->save();

                //update table
                $current_table_id = explode('-', $table_id)[0];
                $table = Table::where([['table_id', $current_table_id], ['status', 'run']])->first();
                $new_status = 'ready';
                $table->status = $new_status; // status of table, run -> ready
                $table->save();

                DB::commit();

                // push event to server Pusher to get in other screen
                event(new DisplayBillingTableInWaiterEvent($current_table_id ,$new_status));

                $success = Lang::get('notify.success.pay-bill');
                return redirect()->route('home')->with('success', $success);
            } catch (Exception $e) {
                DB::rollBack();

                return redirect()->back()->with('errors', $e->getMessage());
            }
        } else {
            return view('404');
        }
    }

    /**
     * Export red bill.
     *
     * @param $table_id
     * @return \Illuminate\Http\Response
     */
    protected function exportRedBill($table_id)
    {
        $user = Auth::user();
        $bill = Bill::where([['table_id', $table_id], ['status', 'new']])->first();
        $now = date("Y-m-d H:i:s");

        $output = $this->getBillDetail($bill);
        $bill_details = $output[0];
        $vndPrice  = $output[1];

        // $pdf = PDF::loadView('user.receptionist.bill.pay-bill.red-bill',
        //     compact('bill', 'now', 'bill_details', 'vndPrice', 'usdPrice'));
        // return $pdf->stream();
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML(\View::make('user.receptionist.bill.pay-bill.red-bill',
            compact('user', 'bill', 'now', 'bill_details', 'vndPrice')));
        $mpdf->debug = true;
        // $mpdf->Output(); // display pdf view
        $mpdf->Output('invoice-ninjarestaurant.pdf', 'D'); // save pdf
    }

    /**
     * Format bill detail.
     *
     * @param $bill
     * @return \Illuminate\Http\Response
     */
    private function getBillDetail($bill)
    {
        //get all bill detail of current bill
        $bill_id = $bill->id;
        $current_bill_details = BillDetail::where([['bill_id', $bill_id], ['status', 'done']])->get();

        // get array include food id and number
        $bill_foods = array();
        foreach ($current_bill_details as $current_bill_detail) {
            $a['food_id'] = $current_bill_detail->food_id;
            $a['number'] = $current_bill_detail->number;
            $bill_foods[] = $a;
        }

        // merge same food id and increase number
        for ($i = 0; $i < count($bill_foods) ; $i++) {
            for ($j = $i + 1; $j < count($bill_foods); $j++) {
                if ($bill_foods[$i]['food_id'] == $bill_foods[$j]['food_id']) {
                    $bill_foods[$i]['number'] = $bill_foods[$i]['number'] + $bill_foods[$j]['number'];
                    $bill_foods[$j]['number'] = 0;
                }
            }
        }

        // delete food_id with number = 0
        $bill_mergeds = array();
        foreach ($bill_foods as $bill_food) {
            if ($bill_food['number'] != 0) {
                $bill_mergeds[] = $bill_food;
            }
        }

        // get name and price for each bill detail
        $bill_details = array();
        $vndPrice  = 0;
        $usdPrice = 0;
        foreach ($bill_mergeds as $bill_merged) {
            $food_id = $bill_merged['food_id'];
            $food = Food::where('id', $food_id)->first();

            // create a new array to store all key and value of each bill detail
            $food_detail = array();
            $food_detail['food_id'] = $food_id;
            $food_detail['number'] = $bill_merged['number'];
            $food_detail['name'] = $food->name;
            $food_detail['vnd_price'] = explode('.', $food->vnd_price)[0];
            $food_detail['usd_price'] = explode('.', $food->usd_price)[0];
            $food_detail['vnd_total'] = $bill_merged['number']*$food->vnd_price;
            $food_detail['usd_total'] = $bill_merged['number']*$food->usd_price;

            $vndPrice = $vndPrice + $food_detail['vnd_total'];
            $usdPrice = $usdPrice + $food_detail['usd_total'];
            // put into a single array
            $bill_details[] = $food_detail;
        }
        $output = array($bill_details, $vndPrice, $usdPrice);
        return $output;
    }
}
