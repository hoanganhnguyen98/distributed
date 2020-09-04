<?php

namespace App\Http\Controllers\CPN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;

class CodeController extends Controller
{
    public function __construct()
    {
        include resource_path('simple_html_dom.php');
    }

    public function getContent(Request $request)
    {
        // Create DOM from URL
        $html = file_get_html('https://247post.vn/dinh-vi-buu-pham/AP3680267402');

        // get link with text format
        $link_content = $html->find('span[class="mailer-prop-value"]', 3);
        echo $link_content;
        // $link_title = $link_content->getElementsByTagName('a')[0]->text();

        // $sort_content = $html->find('span[class="nwsSp descd"]', $id)->text();
        // $img = $html->find('span[class="imgFlt imgNws"]', $id)->find('img', 0)->getAttribute('data-original');
        // $time = $html->find('span[class="updTm dated"]', $id)->text();

        // // explode link to get main content
        // $full_link = $link_content->find('a', 0)->getAttribute('href');
        // $sort_link = explode(env('SORT_LINK'), $full_link)[1];
        // $link = explode('.html', $sort_link)[0];

        // return array($img, $link_title, $sort_content, $link, $time);
    }
}
