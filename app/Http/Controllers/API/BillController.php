<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Food;
use App\Model\Bill;
use App\Model\BillDetail;
use App\Http\Resources\CurrentCart as CurrentCart;

class BillController extends BaseController
{
    /**
     * Create new bill.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

    protected function getCurrentCart($user_id)
    {
    	$carts = BillDetail::where([['user_id', $user_id], ['status', 'new']])->get();
    
        return $this->sendResponse(CurrentCart::collection($carts), 'Carts retrieved successfully.');
    }
}
