<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//存储商户号到数据库
// $conn = mysql_connect('211.66.88.168','zhijie','bgyrtksithv,1*&($AC');
// mysql_select_db('yizhi',$conn);
$sql = "update adminorder set out_trade_no = '".WxPayConfig::MCHID.date("YmdHis")."' where order_num = '".$_GET['order_num']."'";
// mysql_query($sql,$conn);
// mysql_close($conn);
$dsn = "mysql:host=211.66.88.168;dbname=yizhi";
$db = new PDO($dsn, 'zhijie', 'bgyrtksithv,1*&($AC');
$db->exec($sql);

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($_GET['content']);
$input->SetAttach("城际快车");
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($_GET['price']*100);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("城际快车");
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
$jsApiParameters = $tools->GetJsApiParameters($order);
echo $jsApiParameters;
?>

<!-- <html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付-城际快车</title>
    <script type="text/javascript" src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>

	</script>
</head>
<body>
<input type="hidden" name="order_num" id="order_num" value="<?php echo $_GET['order_num'];?>">

</body>
<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				$.ajaxSetup({
			        async: false
			    });
				$.post('http://211.66.88.168/yizhi/server.php/pay/payCallBack','msg='+res.err_msg+"&order_num="+$('#order_num').val(),function(data){

				});

				// alert(res.err_code+res.err_desc+res.err_msg);
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	callpay();
</script>
</html> -->