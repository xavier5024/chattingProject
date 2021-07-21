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

class ChatController extends Controller
{
    public function commonChattingLog(Request $request)
    {   
        $common_chat = DB::table('common_chats AS cc')->select("cc.id", "cc.content", "cc.regDate", "cc.type", "cc.file", "users.name", "users.profile_src")->join("users","cc.id", "users.id")->get();
        return $common_chat;
    }
}