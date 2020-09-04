<?php

namespace App\Http\Controllers\CPN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;

class CodeController extends Controller
{
    public function getContent(Request $request){
        $key = 'EA192662076VN';
        $homepage = file_get_contents('https://247post.vn/dinh-vi-buu-pham/AP3680267402');
        echo $homepage;
    }
}
