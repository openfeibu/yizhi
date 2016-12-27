<?php
		// include('./class.php');
		// $weixin=new class_weixin_adv("wx6116e5d137a3c7f8", "4113d202fa8df86a65eaed1d7b6dd51d");
		// $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx6116e5d137a3c7f8&secret=4113d202fa8df86a65eaed1d7b6dd51d&code=".$_GET['code']."&grant_type=authorization_code";
		// $res = $weixin->https_request($url);//调用SDK方法获取到res 从中可以得到openid
		// $res=(json_decode($res, true));//转换成array 方便调用openid
		// $row=$weixin->get_user_info($res['openid']);
		// echo "<pre>";
		// var_dump($row);

		// $appid = "wx6116e5d137a3c7f8";
		// $secret = "4113d202fa8df86a65eaed1d7b6dd51d";
		// $code = $_GET["code"];

		// //第一步:取全局access_token
		// $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
		// $token = getJson($url);

		// //第二步:取得openid
		// $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		// $oauth2 = getJson($oauth2Url);

		// //第三步:根据全局access_token和openid查询用户信息
		// $access_token = $token["access_token"];
		// $openid = $oauth2['openid'];
		// $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
		// $userinfo = getJson($get_user_info_url);

		// //打印用户信息
		// print_r($userinfo);

		// function getJson($url){
		//     $ch = curl_init();
		//     curl_setopt($ch, CURLOPT_URL, $url);
		//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//     $output = curl_exec($ch);
		//     curl_close($ch);
		//     return json_decode($output, true);
		// }

		$code = $_GET['code'];
		$state = $_GET['state'];

		/*根据code获取用户openid*/
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx6116e5d137a3c7f8&secret=4113d202fa8df86a65eaed1d7b6dd51d&code=".$code."&grant_type=authorization_code";

		$abs = file_get_contents($url);
		$obj=json_decode($abs);
		$access_token = $obj->access_token;
		$openid = $obj->openid;
		/*根据code获取用户openid end*/


		/*根据用户openid获取用户基本信息*/
		$abs_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
		$abs_url_data = file_get_contents($abs_url);
		$obj_data=json_decode($abs_url_data);
		$url = "http://211.66.88.168/yizhi/server.php/user/handleLogin?openid=".$obj_data->openid."&nickname=".$obj_data->nickname."&sex=".$obj_data->sex."&img=".$obj_data->headimgurl."&city=".$obj_data->city;
		header("Location:".$url);
?>