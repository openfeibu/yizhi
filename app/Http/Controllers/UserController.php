<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Controllers\Controller;
use App\User;
use DB;

class UserController extends Controller
{
	public function login(){
		$appid='wx0e7bd4358af64230';
		$redirect_uri = urlencode ( 'http://api.yizhizulin.com/wechat/post.php' );
		$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
		header("Location:".$url);
	}
	public function handleLogin(Request $request){
		$saveUser = User::saveUser($request->openid,$request->nickname,$request->img,$request->sex,$request->city);
		header("Location:http://www.yizhizulin.com/index.html?openid=".$request->openid);
		/* if($saveUser == 200){
			return [
				'code'=>200,
				'detail'=>"请求成功",
				'data' => [
					'openid' => $request->openid,
					'nickname' => $request->nickname,
					'img_url' => $request->img,
				]
			];
		}else{
			return [
				'code'=>403,
				'detail'=>"请求失败"
			];
		} */
	}

	public function getUserInfo(Request $request){
		if($this->checkOpenid($request) != 200){
            return $this->checkOpenid($request);
        }
		$user  = User::select(DB::raw('nickname,img_url'))->where('openid',$request->openid)->first();
		return [
				'code'=>200,
				'detail'=>"请求成功",
				'data' => $user
			];
	}
}
