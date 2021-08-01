<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request as Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use App\Notifications\privateWhisper;
use App\Notifications\notiClear;

use App\Models\User;

class ChatController extends Controller
{
    public function commonChattingLog(Request $request)
    {   
        $common_chat = DB::table('common_chats AS cc')->select("cc.id", "cc.content", "cc.regDate", "cc.type", "cc.file", "users.name", "users.profile_src")->join("users","cc.id", "users.id")->get();
        return $common_chat;
    }
    public function privateChattingLog(Request $request)
    {
        $result = array();
        $privateToId = $request->get("privateToId");
        $userId = Auth::id();
        $channelname = ($userId < $privateToId) ? $userId."to".$privateToId : $privateToId."to".$userId;
        $chat = DB::table('notifications')->where("notifiable_id", $userId)->where("data->send_id", $privateToId);
        $chatting = DB::table('notifications')->where("notifiable_id", $privateToId)->where("data->send_id", $userId)->union($chat)->orderBy("created_at")->get()->toArray();

        $user = Auth::user();

        $user->unreadNotifications()->where('type', 'App\Notifications\privateWhisper')->where("data->send_id", $privateToId)->get()->map(function($n) {
            $n->markAsRead();
        });	
        $user->notify(new notiClear($privateToId));

        for($idx=0; $idx<count($chatting); $idx++){
            $chatting[$idx]->data = json_decode($chatting[$idx]->data, true);
        }

        $result["result"] = true;
        $result["channelname"] = $channelname;
        $result["chatting"] = $chatting;

        return $result;
    }
    public function commonChatting(Request $request)
    {
        $result = array();
        broadcast(new \App\Events\CommonChatting());
        $result["result"] = true;
        return $result;
    }
    public function privateChatting(Request $request)
    {
        $result      = array();
        $user        = Auth::user();
        $message     = $request->get("message");
        $privateTo   = $request->get("privateTo");
        $channel_name = $request->get("channel_name");

        $privateUser = User::find($privateTo);
        $privateUser->notify(new privateWhisper($user->id, $privateTo, $message));
        broadcast(new \App\Events\PrivateChatting($channel_name));
        $result["result"] = true;
        return $result;
    }

    public function privateRead(Request $request){
        $result = array();
        $privateTo = $request->get("privateTo");
        $user = Auth::user();
        $user->unreadNotifications()->where('type', 'App\Notifications\privateWhisper')->where("data->send_id", $privateTo)->get()->map(function($n) {
            $n->markAsRead();
        });	
        $user->notify(new notiClear($privateTo));
        $result["result"] = true;
        return $result;
    }
}