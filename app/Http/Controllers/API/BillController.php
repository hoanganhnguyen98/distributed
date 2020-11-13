<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Food;
use App\Model\Bill;
use App\Model\BillDetail;
use App\Http\Resources\CurrentCart as CurrentCart;
use App\Http\Resources\CartHistory as CartHistory;
use Carbon\Carbon;

class BillController extends BaseController
{
    protected function addToCart(Request $request)
    {
        BillDetail::create([
            'bill_id' => 'app',
            'status' => 'new',
            'food_id' => $request->food_id,
            'number' => $request->number,
            'user_id' => $request->user_id,
            'food_name' => $request->food_name,
            'image' => $request->image,
            'price' => $request->price
        ]);

        return $this->sendResponse('Added', 'Add to cart successfully.');
    }

    protected function removeCart($id)
    {
        $food = BillDetail::where([['id', $id], ['status', 'new']])->first();
        $food->delete();

        return $this->sendResponse('Removed', 'Remove food successfully.');
    }

    protected function getCurrentCart($user_id)
    {
    	$carts = BillDetail::where([['user_id', $user_id], ['status', 'new']])->get();
    
        return $this->sendResponse(CurrentCart::collection($carts), 'Carts retrieved successfully.');
    }

    protected function getHistory($user_id)
    {
        $historys = Bill::where('receptionist_id', $user_id)->get();
    
        return $this->sendResponse(CartHistory::collection($historys), 'Cart histories retrieved successfully.');
    }

    protected function getHistoryDetail($bill_id)
    {
        $historys = BillDetail::where('bill_id', $bill_id)->get();
    
        return $this->sendResponse(CurrentCart::collection($historys), 'Detail histories retrieved successfully.');
    }

    protected function orderNow(Request $request)
    {
        // Bill::create([
        //     'receptionist_id' => $request->user_id,
        //     'table_id' => 'app',
        //     'customer_name' => $request->name,
        //     'street' => $request->address,
        //     'district' => $request->address,
        //     'city' => $request->address,
        //     'phone' => $request->phone,
        //     'email' => $request->email,
        //     'total_price' => $request->total_price
        // ]);
        $id = Bill::insertGetId([
            'receptionist_id' => $request->user_id,
            'table_id' => 'app',
            'customer_name' => $request->name,
            'street' => $request->address,
            'district' => $request->address,
            'city' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'total_price' => $request->total_price,
            "created_at" =>  Carbon::now(),
            "updated_at" => Carbon::now(), 
        ]);

        $carts = BillDetail::where([['user_id', $request->user_id], ['status', 'new']])->get();

        foreach ($carts as $cart) {
            $cart->bill_id = $id;
            $cart->status = 'done';
            $cart->save();
        }

        return $this->sendResponse('Order', 'Order successfully.');
    }
}
