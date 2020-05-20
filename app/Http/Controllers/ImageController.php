<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use Illuminate\Support\Facades\Validator;
use Cloudder;
use App\Events\DemoPusherEvent;

class ImageController extends Controller
{
    public function getUrl()
    {
        return view('imagetopoint');
    }

    public function getImage(Request $request)
    {
        $rules = [
            'image' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return redirect()->back()->with('errors', "Image null!");
        }

        if ($request->hasFile('image')){
            // create path to store in cloud
            $public_id = "ninja_restaurant/imagetopoint/imagetopoint";
            // upload to cloud
            Cloudder::upload($request->file('image'), $public_id);
            // get url of image
            $resize = array("width" => 300, "height" => 300, "crop" => "fill");
            $imageUrl = Cloudder::show($public_id, $resize);
        }

        // Truyền message lên server Pusher
        event(new DemoPusherEvent($imageUrl));

        // return redirect()->back()->with('success', "Upload image successfully!")->with('image', $img_url);
    }
}
