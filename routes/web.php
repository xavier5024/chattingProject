<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TestController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', [TestController::class,'test']);
Route::get('testing', function(Request $request){
    broadcast(new \App\Events\CommonChatting("common"));
});


Route::post('data/login', [AuthController::class,'login']);
Route::match(array('GET', 'POST') , 'data/logout', [AuthController::class,'logout']);
Route::post('data/join', [AuthController::class,'join']);

Route::post('/data/commonChatting', function(Request $request){
	broadcast(new \App\Events\CommonChatting("common"));
    return "ok";
})->middleware('auth');

Route::post('/data/commonChattingLog', [ChatController::class, 'commonChattingLog'])->middleware('auth')->middleware('auth');

Route::get('/{any?}', function () {
    $user = Auth::user();
    $userInfo = array();
    if ($user !== null) {
        // 로그인 상태
        $userInfo = $user;
        $userInfo['auth'] = true;
    } else {
        // 로그아웃 상태
        $userInfo['auth'] = false;
    }
    return view('App', array('user'=>json_encode($userInfo)));
});

