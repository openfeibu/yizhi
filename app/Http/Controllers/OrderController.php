<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Controllers\Controller;
use App\Order;
use DB;

class OrderController extends Controller
{
	public function bookOrder(Request $request){
        $rules = [
            'openid' => 'required',
            'car_id' => 'required',
            'mobile' => 'required',
            'username' => 'required',
            'number' => 'required',
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

        $bookOrder = Order::bookOrder($request->openid,$request->car_id,$request->mobile,$request->username,$request->number,$time);
        return [
            'code'=>200,
            'detail'=>"请求成功",
            'data' => [
                'order_num' => $bookOrder,
            ]
        ];
    }
}
