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

class MemberController extends BaseController
{
   public function memberList(){
     return DB::table('users')->select('id', 'user_id', 'name', 'tel', 'profile_src', 'email', 'email_verified_at', 'password', 'remember_token')->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") AS created_at')->get();
   }

    public function memberRead(Request $request){
        $member_id = $request->get("member_id");
        return DB::table('users')->where('id', $member_id)->first();
    }
    public function memberRegister(Request $request){
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

        $insertdata = array('user_id' => $data["user_id"], 'name'=>$data["name"], 'password' => Hash::make($data["password"]), 'email' => $data["email"], 'tel' => $data["tel"]);

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

        DB::table("users")->insert($insertdata);

        $result["message"] = "생성이 완료되었습니다.";
        $result['result'] = true;

        return $result;
    }

    public function memberUpdate(Request $request){
        $result = array();
        $data = json_decode($request->get("data"), true);
        if (count($data)) {
            foreach ($data as $key => $value) {
                $request->request->set($key, $value);
            }
            request()->request->remove('data');
        }

        $validator = Validator::make($request->all(), [
            'member_id' => 'required|string|max:100|exists:users,id',
            'name'  => 'required|string|max:100',
            'email' => ['required','email:rfc,dns','max:50',Rule::unique('users')->ignore($request->get('member_id'))],
            'tel'   => ['required','string','max:13',Rule::unique('users')->ignore($request->get('member_id'))],
            'password' => 'string|min:8|max:255|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[@!$#%]).*$/',
            'password_confirmation' => 'string|min:8|max:255|same:password',
            'profile_img' => 'image|max:4048'
        ]);
        
        if($validator->fails()) {         
            return array(
                'result' => false,
                'message' => implode("<br>", Arr::flatten($validator->messages()->toArray()))
            );
        }

        $updatedata = array('name'=>$data["name"], 'email' => $data["email"], 'tel' => $data["tel"]);

        if(isset($data["password"]) && $data["password"]){
            $updatedata['password'] = Hash::make($data["password"]);
        }

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
                $updatedata["profile_src"] = "/data".$path."/".$physical_name;
            }
        }

        DB::table("users")->where('id', $request->get('member_id'))->update($updatedata);

        $result["message"] = "수정이 완료되었습니다.";
        $result['result'] = true;

        return $result;
    }

}