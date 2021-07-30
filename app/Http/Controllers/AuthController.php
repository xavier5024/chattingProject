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
    public function join(Request $request): array
    {
        $result = array();
        $data = json_decode($request->get("data"), true);
        if (count($data)) {
            foreach ($data as $key => $value) {
                $request->request->set($key, $value);
            }
            request()->request->remove('data');
        }

        $validator = Validator::make($request->all(), [
            'id'    => 'required|string|max:100|unique:users,user_id',
            'name'  => 'required|string|max:100',
            'email' => 'required|email:rfc,dns|max:50|unique:users',
            'tel'   => 'required|string|max:13|unique:users',
            'password' => 'required|string|min:8|max:255|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[@!$#%]).*$/',
            'password_confirmation' => 'string|min:8|max:255|same:password',
            'profile_img' => 'image|max:4048'
        ]);
        
        if($validator->fails()) {         
            return array(
                'result' => false,
                'message' => implode("<br>", Arr::flatten($validator->messages()->toArray()))
            );
        }

        $insertdata = array('user_id' => $data["id"], 'name'=>$data["name"], 'password' => Hash::make($data["password"]), 'email' => $data["email"], 'tel' => $data["tel"]);

        if (request()->hasFile('profile_img') && request()->profile_img->isValid()) {
            $logical_name = request()->file('profile_img')->getClientOriginalName();
            $ext = substr($logical_name, strrpos($logical_name, "."));
            $physical_name = round(microtime(true)).$ext;
            $filepath = public_path("data");
            $path = "/".date("Y")."/".date("md");
            if (!is_dir(public_path("data")."/".date("Y"))) {
                mkdir(public_path("data")."/".date("Y"));
            }
            if (!is_dir(public_path("data")."/".$path)) {
                mkdir(public_path("data")."/". $path);
            }
            $file_result = request()->file('profile_img')->move($filepath.$path,$physical_name);
            if ($file_result) {
                $insertdata["profile_src"] = "/data".$path."/".$physical_name;
            }
        }

        $user_id = DB::table('users')->insertGetId($insertdata);

        $user = User::find($user_id);
        // 로그인 진행
        Auth::login($user, true);

        $result['result'] = true;
        $userInfo = $user->toArray();
        $userInfo['auth'] = true;
        $result["message"] = "가입이 완료되었습니다.";
        $result['data'] = $userInfo;

        return $result;

    }

}
