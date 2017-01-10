<?php
	function http_request($url,$data=array()){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		// 我们在POST数据哦！
		curl_setopt($ch, CURLOPT_POST, 1);
		// 把post的变量加上
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	$json_token=http_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx0e7bd4358af64230&secret=2c0e08dceccfbf27a2df74ee68518693");
	$access_token=json_decode($json_token,true);
	//获得access_token
	$access_token=$access_token['access_token'];
	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
	$array2 = array(
			'touser' => 'ooeMMw3yemwepRcUAMVtN-qTttmQ',
			'template_id' => 'qEyacY6_43qy8KKlFbpm4nWHc0tSnWFk-Rm2ygUyKPo',
			'topcolor' => '#ff0000',
			'data' => array(
					'first' => array(
							'value' => urldecode('你有新的订单，请注意查看后台'),
							'color' => '#ff0000'
						),
					'orderno' => array(
							'value' => urldecode($_GET['order_num']),
							'color' => '#743a3a'
						),
					'refundno' => array(
							'value' => urldecode($_GET['number']),
							'color' => '#743a3a'
						),
					'refundproduct' => array(
							'value' => urldecode($_GET['all_price']),
							'color' => '#743a3a'
						),
					'remark' => array(
							'value' => urldecode("下单时间：".date('Y-m-d H:i:s')),
							'color' => '#743a3a'
						),
				)
		);
	$res2=http_request($url,$array2);
	//跳转到订单详情页面
	header("Location:http://yizhi.feibu.info/yizhidemo/orderDetail.php?order_num=".$_GET['order_num']);
