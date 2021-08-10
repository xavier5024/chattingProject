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
use Illuminate\Support\Arr;

use App\Models\User;

class MemberController extends BaseController
{
   public function memberList(){
     return DB::table('users')->select('id', 'user_id', 'name', 'tel', 'profile_src', 'email', 'email_verified_at', 'password', 'remember_token')->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") AS created_at')->get();
   }

}
