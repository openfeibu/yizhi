<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Controllers\Controller;
use App\Paper;
use App\Order;
use App\Overplus;
use App\Schedules;
use DB;

class CommonController extends Controller
{
	public function getUserNotice(Request $request){
        $rules = [
            'paper_id' => 'required',
        ];
        if($this->validateParameter($request,$rules)!= 200){
            return $this->validateParameter($request,$rules);
        }
        if($this->checkOpenid($request) != 200){
            return $this->checkOpenid($request);
        }

        $paper = Paper::getPaper($request->paper_id);
        return [
            'code'=>200,
            'detail'=>"请求成功",
            'data' => $paper
        ];
    }

    public function payCallBack(Request $request){
        $rules = [
            'msg' => 'required',
            // 'openid' => 'required',
            'order_num' => 'required',
        ];
        if($this->validateParameter($request,$rules)!= 200){
            return $this->validateParameter($request,$rules);
        }
        // if($this->checkOpenid($request) != 200){
        //     return $this->checkOpenid($request);
        // }
        if($request->msg == "get_brand_wcpay_request:ok"){
            //支付成功
            $order = Order::where('order_num',$request->order_num)->first();
            $order->status = 'payment';
            $order->save();
            $time = explode(' ' , $order->start_time);
            $overplus = Overplus::where('schedules_id',$order->schedules_id)->where('time',$time[0])->first();
            if($overplus){
                //如果存在今日余票数据，则更新座位，
                $overplus->overplus = $overplus->overplus - $order->number;
                $overplus->save();
            }else{
                //如果不存在今日余票数据，则插入数据
                $schedules = Schedules::select(DB::raw('seat_number'))->where('id',$order->schedules_id)->first();
                $overp = new Overplus;
                $overp->schedules_id = $order->schedules_id;
                $overp->time = $time[0];
                $overp->overplus = $schedules->seat_number - $order->number;
                $overp->save();
            }
            return [
                'code'=>200,
                'detail'=>"支付成功",
                'data' => [
                    'number' => $order->number,
                    'all_price' => $order->all_price,
                    'order_num' => $order->order_num,
                ]
            ];
            // header("Location:http://yizhi.feibu.info/wechat/send_msg.php?number=".$order->number."&all_price=".$order->all_price."&order_num=".$order->order_num);
        }else if ($request->msg == "get_brand_wcpay_request:fail") {
            //支付失败
            return [
                'code'=>403,
                'detail'=>"支付失败",
                'data' => [
                    'order_num' => $order->order_num,
                ]
            ];
        }
    }

    public function payOrder(Request $request){
        $rules = [
            'openid' => 'required',
            'order_num' => 'required',
        ];
        if($this->validateParameter($request,$rules)!= 200){
            return $this->validateParameter($request,$rules);
        }
        if($this->checkOpenid($request) != 200){
            return $this->checkOpenid($request);
        }

        $getOrderDetail = Order::getOrderDetail($request->order_num);
        //查询余票够不够
        $schedules = Schedules::select(DB::raw('seat_number'))->where('id',$getOrderDetail->schedules_id)->first();
        $time = explode(' ' , $getOrderDetail->start_time);
        $overplus = Overplus::where('schedules_id',$getOrderDetail->schedules_id)->where('time',$time[0])->first();
        if($overplus){
            $overplus_num = $overplus->overplus;
        }else{
            $overplus_num = $schedules->seat_number;
        }

        if($getOrderDetail->number > $overplus_num){
            return [
                'code'=>403,
                'detail'=>"余票不足",
                'data' =>''
            ];
        }else{
            $content = $getOrderDetail->start_details."--".$getOrderDetail->end_details;
            return [
                'code'=>200,
                'detail'=>"请求成功",
                'data' => [
                    'content' => $content,
                    'price' => $getOrderDetail->all_price,
                    'order_num' =>  $request->order_num,
                    'openid' =>  $request->openid,
                ]
            ];
        }
    }
}
