<?php

namespace App\Http\Controllers\KitchenManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Table;
use App\Model\BillDetail;
use App\Model\Bill;
use App\Model\Food;
use Auth;

class OrderController extends Controller
{
    /**
     * Show table detail
     *
     * @param $table_id
     * @return \Illuminate\Http\Response
     */
    protected function showTableDetail($table_id)
    {
        $area = Auth::user()->area;

        // get table list
        $tables = Table::where('area', $area)->get();
        $current_orders = BillDetail::where('status', 'new')->get();

        // get all order in kitchen manager area
        $orders = array();
        foreach ($current_orders as $order) {
            // get current table id of order with bill id
            $table_id = Bill::where('id', $order->bill_id)->first()->table_id;
            // get current area of bill
            $bill_area = explode('-', $table_id)[1];

            if ($bill_area == $area) {
                $order_detail = array();
                $order_detail['table'] = explode('-', $table_id)[0];
                $order_detail['food_name'] = Food::where('id', $order->food_id)->first()->name;
                $order_detail['number'] = $order->number;
                $orders[] = $order_detail;
            }
        }

        $prepare_orders = BillDetail::where('status', 'prepare')->get();
        $done_orders = BillDetail::where('status', 'done')->get();

        return view('user.kitchen-manager.home.table-detail', compact('tables', 'orders'));
    }
}
