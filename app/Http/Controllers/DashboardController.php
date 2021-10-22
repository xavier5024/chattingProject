<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request as Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

use App\Models\User;

class DashboardController extends BaseController
{
    public function dashboardList(Request $request){
        $result = array();
        $result["total_user"] = DB::table('users')->count();
        $result["total_chats"] = DB::table('common_chats')->count();
        $result["total_member_free_board"] = DB::table('member_free_board')->where("iscomment", "N")->count();
        return $result;
    }
}