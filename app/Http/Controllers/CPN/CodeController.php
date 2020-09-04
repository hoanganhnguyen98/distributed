<?php

namespace App\Http\Controllers\CPN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;

class CodeController extends Controller
{
    public function getContent(Request $request){
        $arrayCode = [];

        // Excel::load($request->file, function ($reader) {

        //     foreach ($reader->toArray() as $row) {
        //         echo $row;
        //     }
        // });
        $rows = Excel::load($request->file)->get();

        dd($rows)
    }
}
