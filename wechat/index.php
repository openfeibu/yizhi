

	<?php
		// $appid='wx6116e5d137a3c7f8';
		// $redirect_uri = urlencode ( 'http://feibu.info/test/post.php' );
		// $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
		// header("Location:".$url);

		$appid='wx6116e5d137a3c7f8';
		$redirect_uri = urlencode ( 'http://yizhi.feibu.info/wechat/post.php' );
		$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
		header("Location:".$url);
	 ?>
	<!-- wx6116e5d137a3c7f8
	4113d202fa8df86a65eaed1d7b6dd51d -->

