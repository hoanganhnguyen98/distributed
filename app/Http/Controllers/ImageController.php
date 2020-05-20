<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use Illuminate\Support\Facades\Validator;
use Cloudder;

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
            $img_url = Cloudder::show($public_id, $resize);
        }

        return redirect()->back()->with([['success', "Upload image successfully!"],['image', $img_url]]);
    }
}
