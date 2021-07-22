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
    public function test1(Request $request)
    {   
        $users = User::join("common_chats AS cc", "users.id", "cc.id")->get();

        $users = json_decode($users, true);

        print_r2($users);
    }
    public function test2(Request $request)
    {   
        $users = User::join("common_chats AS cc", "users.id", "cc.id")->where("cc.id", '3')->orderBy("cc.id")->dd();

    }
    public function test3(Request $request)
    {   
        $user = User::get()->toArray();

        //print_r2($user);
        
        $users = User::find("1")->dd();


    }
    public function test4(Request $request)
    {   
        $user = Auth::user();
        $users = json_decode(User::withTrashed()->get(), true);
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