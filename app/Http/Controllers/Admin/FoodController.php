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
        if (Auth::user()->role == 'admin') {
            $types = Type::all();
            $sources = Source::all();

            return view('user.admin.food.create-food', compact('types', 'sources'));
        } else {
            return view('404');
        }
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
                'vnd_price' => ['required', 'regex:/[0-9]/', 'max:255'],
                'usd_price' => ['required', 'regex:/[0-9]/', 'max:255'],
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
                $resize = array("width" => 300, "height" => 300, "crop" => "fill");
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
        if (Auth::user()->role == 'admin') {
            $foods = Food::sortable()->paginate(10);
            return view('user.admin.food.food-list', compact('foods'));
        } else {
            return view('404');
        }
    }

    /**
     * Delete food.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    protected function deleteFood($id)
    {
        try {
            DB::beginTransaction();

            $food = Food::where('id', $id)->first();
            $food->delete();

            DB::commit();

            $success = Lang::get('notify.success.delete-food');
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
    protected function showEditFoodForm($id)
    {
        if (Auth::user()->role == 'admin') {
            $food = Food::where('id', $id)->first();
            $types = Type::all();
            $sources = Source::all();

            return view('user.admin.food.food-edit.food-edit', compact('food', 'types', 'sources'));
        } else {
            return view('404');
        }
    }

    /**
     * Edit food information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function editFoodInformation(Request $request)
    {
        try {
            DB::beginTransaction();

            $rules = [
                'id' => ['required'],
                'name' => ['required', 'string', 'max:255'],
                'material' => ['required', 'string', 'max:255'],
                'vnd_price' => ['required', 'numeric', 'max:255'],
                'usd_price' => ['required', 'numeric', 'max:255'],
            ];
            // dd($request->all()); exit();

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $food = Food::where('id', $request->id)->first();
            $food->admin_id = Auth::user()->user_id;
            $food->name = $request->name;
            $food->material = $request->material;
            $food->type = $request->type;
            $food->source = $request->source;
            $food->vnd_price = $request->vnd_price;
            $food->usd_price = $request->usd_price;
            $food->save();

            DB::commit();

            $success = Lang::get('notify.success.edit-food-info');
            return redirect()->back()->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        }
    }

    /**
     * Change image.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function changeFoodImage(Request $request)
    {
       try {
            DB::beginTransaction();

            $rules = [
                'id' => ['required'],
                'image' => ['required'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // get food need to change image
            $food = Food::where('id', $id)->first();

            // name of image as unix time, get old name of image to update
            // example string: http://res.cloudinary.com/ninjahh/image/upload/c_pad,h_300,w_300/v1/ninja_restaurant/foods/1574558041.png
            // explode string to get old name
            $image_name = explode('ninja_restaurant/foods/', $food->image)[1];
            $old_name = explode('.', $image_name)[0];

            // get image to store in cloud
            if ($request->hasFile('image')){
                // create path to store in cloud
                $public_id = "ninja_restaurant/foods/".$old_name;
                // upload to cloud
                Cloudder::upload($request->file('image'), $public_id);
                // get url of image
                $resize = array("width" => 300, "height" => 300, "crop" => "fill");
                $img_url = Cloudder::show($public_id, $resize);
            }

            // save new image
            $user->image = $img_url;
            $user->save();

            DB::commit();

            $success = Lang::get('notify.success.change-food-image');
            return redirect()->back()->with('success', $success);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', $e->getMessage());
        } 
    }
}
