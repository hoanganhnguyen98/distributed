<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\DemoPusherEvent;

class FrontEndController extends Controller
{
    public function getPusher()
    {
        // gọi ra trang view demo-pusher.blade.php
        return view("demo");
    }

    public function fireEvent()
    {
        // Truyền message lên server Pusher
        // event(new DemoPusherEvent("Hi, I'm Trung Quân. Thanks for", 1));
        // return "Message has been sent.";
    }
}
