<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    /**
     * Show create food form.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showCreateFoodForm()
    {
        return view('user.admin.food.create-food');
    }

    /**
     * Create new food.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function createFood(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'name' => ['required', 'string', 'max:255'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // if ($request->hasFile('image')) {
            //     $image = $request->image;
            //     $avatar = time().'.'.$image->getClientOriginalExtension();
            //     $destinationPath = public_path('\img\avatar');
            //     $image->move($destinationPath, $avatar);
            // }

            //save

            DB::commit();

            $success = Lang::get('notify.success.create');
            return redirect()->back()->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }

    /**
     * Show food list.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showFoodList()
    {
        return view('user.admin.food.food-list');
    }
}
