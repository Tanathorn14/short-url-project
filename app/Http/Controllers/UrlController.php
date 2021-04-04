<?php

namespace App\Http\Controllers;

use App\shortURL;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function index()
    {
        $urls = shortURL::all();
        return view('index',compact('urls'));
    }

    public function create()
    {
        $count = shortURL::count();
        if ($count >= 10)
        {
            return 'เกินกำหนด';
        }
        return view('create');
    }

    public function store(Request $request)
    {
        $long_url = $request->get('long_url');
        $short_url = $this->randString();
        shortURL::create([
            'long_url'=>$long_url,
            'short_url'=>$short_url
        ]);

        return redirect('/')->with('success','บันทึกข้อมูลเรียบร้อยแล้ว');
    }

    public function randString()
    {
        $charectors = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';

        $charLength = strlen($charectors);
        $numLength = strlen($numbers);
        $string = '330';
        for ($i=0;$i< 3;$i++)
        {
            $string.=$charectors[rand(0,$charLength)];
        }

        for ($i=0;$i< 2;$i++)
        {
            $string.=$numbers[rand(0,$numLength-1)];
        }

        return $string;
    }

    public function check($code)
    {
        $result = shortURL::Where('short_url',$code)->first();
        if ($result)
        {
            return redirect()->away($result->long_url);
        }
        return 'ไม่พบรหัส Short URL นี้';
    }
}
