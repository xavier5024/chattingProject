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

class AuthController extends BaseController
{
    public function login(Request $request): array
    {
        $loginResult = array();

        if (!$request->has(['id','password'])) {
            $loginResult['result'] = false;
            $loginResult['message'] = '아이디 또는 비밀번호가 입력되지 않았습니다';

            return $loginResult;
        }

        $id = $request->input('id');
        $password = $request->input('password');

        if ($id === "" || $password === "") {
            $loginResult['result'] = false;
            $loginResult['message'] = '아이디 또는 비밀번호가 입력되지 않았습니다';

            return $loginResult;
        }

        $user = User::where('user_id', $id)->first();

        if ($user === null) {
            $loginResult['result'] = false;
            $loginResult['message'] = '아이디 또는 비밀번호가 올바르지 않습니다';

            return $loginResult;
        }

        if (!Hash::check($password, $user->password)) {
            $loginResult['result'] = false;
            $loginResult['message'] = '아이디 또는 비밀번호가 올바르지 않습니다';

            return $loginResult;
        }

        // 로그인 진행
        Auth::login($user, true);

        $loginResult['result'] = true;
        $userInfo = $user->toArray();
        $userInfo['auth'] = true;
        $loginResult['data'] = $userInfo;

        return $loginResult;
    }

    public function logout(): array
    {
        Auth::logout();
        $user = Auth::user();
        if ($user === null) {
            $logoutResult['result'] = true;
        } else {
            $logoutResult['result'] = false;
            $logoutResult['message'] = '로그인 세션이 올바르게 삭제되지 않았습니다<br/>다시 시도해주세요';
        }

        return $logoutResult;
    }
}
