<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Controllers\Controller;
use App\Order;
use DB;
use App\Http\Controllers\CommonController;

class OrderController extends Controller
{
	public function bookOrder(Request $request){
        date_default_timezone_set("Asia/Shanghai");
        $rules = [
            'openid' => 'required',
            'car_id' => 'required',
            'mobile' => 'required',
            'username' => 'required',
            'number' => 'required',
            'start_details' => 'required',
            'end_details' => 'required',
        ];
        if($this->validateParameter($request,$rules)!= 200){
            return $this->validateParameter($request,$rules);
        }
        if($this->checkOpenid($request) != 200){
            return $this->checkOpenid($request);
        }
        if(empty($request->time)){
            $time = date('Y-m-d');
        }else{
            $time = $request->time;
        }
        $overplus = Order::getOverplus($request->car_id);
        if($request->number > $overplus){
            return [
                'code'=>403,
                'detail'=>"余票不足",
                'data' => $overplus
            ];
        }
        $bookOrder = Order::bookOrder($request->openid,$request->car_id,$request->mobile,$request->username,$request->number,$time,$request->start_details,$request->end_details);
        // return [
        //     'code'=>200,
        //     'detail'=>"请求成功",
        //     'data' => $bookOrder
        // ];
        $content = $request->start_details."--".$request->end_details;
        header("Location:http://yizhi.feibu.info/wechat/weChatPay/example/jsapi.php?content=".$content."&price=".$bookOrder['all_price']."&order_num=".$bookOrder['order_num']."");

    }

    public function getMyOrder(Request $request){
        $order = Order::getMyOrder($request->openid);
        return [
            'code'=>200,
            'detail'=>"请求成功",
            'data' => $order
        ];
    }


}
