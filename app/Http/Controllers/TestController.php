<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request as Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use App\Models\User;

class TestController extends Controller
{
    public function test(Request $request)
    {   
        $user = Auth::user();

        
        //User::find(7)->delete();

        //$users = DB::table("users")->get()->where('deleted_at', null)->toArray();

        $users = json_decode(User::withTrashed()->get(), true);

        print_r2($users);
        
        //print_r2($users);

        //return $users;
    }
}

function print_r2($var)
{
    ob_start();
    print_r($var);
    $str = ob_get_contents();
    ob_end_clean();
    $str = str_replace(" ", "&nbsp;", $str);
    echo nl2br("<span style='font-family:Tahoma, 굴림; font-size:9pt;'>$str</span>");
}