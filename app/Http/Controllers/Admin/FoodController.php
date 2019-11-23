<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Food;
use App\Model\Type;
use App\Model\Source;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Cloudder;
use Auth;

class FoodController extends Controller
{
    /**
     * Show create food form.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showCreateFoodForm()
    {
        $types = Type::all();
        $sources = Source::all();

        return view('user.admin.food.create-food', compact('types', 'sources'));
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
                'material' => ['required', 'string', 'max:255'],
                'vnd_price' => ['required', 'max:255'],
                'usd_price'  => ['required', 'max:255'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // get image to store in cloud
            if ($request->hasFile('image')){
                // create path to store in cloud
                $public_id = "ninja_restaurant/foods/".time();
                // upload to cloud
                Cloudder::upload($request->file('image'), $public_id);
                // get url of image
                $resize = array("width" => 300, "height" => 300, "crop" => "pad");
                $img_url = Cloudder::show($public_id, $resize);
            }

            Food::create([
                'admin_id' => Auth::user()->user_id,
                'name' => $request->name,
                'image' => $img_url,
                'type' => $request->type,
                'source' => $request->source,
                'material' => $request->material,
                'vnd_price' => $request->vnd_price,
                'usd_price' => $request->usd_price,
            ]);

            DB::commit();

            $success = Lang::get('notify.success.create-food');
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
        $foods = Food::all();
        return view('user.admin.food.food-list', compact('foods'));
    }
}
