<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\User;
use App\Order;
use App\Schedules;
use App\Overplus;
use Session;
use DB;

class Order extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    protected $table = 'adminorder';

    public static function getOverplus($overplus){
        $over = Overplus::where('schedules_id',$overplus)->first();
        return isset($over->overplus) ? $over->overplus : 0;
    }

    public static function bookOrder($openid,$car_id,$mobile,$username,$number,$time,$start_details,$end_details){
        date_default_timezone_set("Asia/Shanghai");
    	Session::set('time',$time);
    	$getSchedulesOne = Schedules::select(DB::raw('adminschedules.id as car_id,adminschedules.time,adminschedules.start_place,adminschedules.end_place,adminschedules.seat_number,adminschedules.price,overPlus.overplus'))
                        ->leftJoin('overPlus', function($join) {
                                $join->on('adminschedules.id', '=', 'overPlus.schedules_id')
                                     ->where('overPlus.time','=',Session::get('time'));
                        })
                        ->where('adminschedules.id', $car_id)
                        ->where('status', 1)
                        ->whereNull('deleted_at')
                        ->first();

        $getSchedulesOne['time'] = $time." ".$getSchedulesOne['time'];
        if(empty($getSchedulesOne['overplus'])){
            $getSchedulesOne['overplus'] = $getSchedulesOne['seat_number'];
        }

        $user = User::select(DB::raw('id'))->where('openid',$openid)->first();

        $order = new Order;
        $order->order_num = "YZ".date('YmdHis').$user->id;
        $order->user_id = $user->id;
        $order->schedules_id = $car_id;
        $order->username = $username;
        $order->mobile = $mobile;
        $order->start_time = $getSchedulesOne['time'];
        $order->number = $number;
        $order->all_price = $getSchedulesOne['price']*$number;
        $order->price = $getSchedulesOne['price'];
        $order->start_details = $start_details;
        $order->end_details = $end_details;
        $order->save();

        return [
            'order_num' => "YZ".date('YmdHis').$user->id,
            'all_price' => $getSchedulesOne['price']*$number,
        ];
    }
    public static function getMyOrder($openid){
        $user = User::select(DB::raw('id'))->where('openid',$openid)->first();
        return Order::select(DB::raw("adminorder.order_num,adminorder.start_details,adminorder.end_details,username,mobile,adminorder.start_time,adminorder.number,adminorder.price,adminorder.all_price,case adminorder.status
                        WHEN 'notpayment' THEN '未付款'
                        WHEN 'finished' THEN '已付款'
                        WHEN 'refunded' THEN '已退款'
                        else '待定状态'
                        end  as status,adminschedules.start_place,adminschedules.end_place,adminorder.created_at"))
                    ->leftJoin('adminschedules','adminorder.schedules_id','=','adminschedules.id')
                    ->where('user_id',$user->id)
                    ->orderBy('adminorder.id','DESC')
                    ->get();
    }

    public static function getOrderDetail($order_num){
        return Order::select(DB::raw("adminorder.order_num,adminorder.start_details,adminorder.end_details,username,mobile,adminorder.start_time,adminorder.number,adminorder.price,adminorder.all_price,case adminorder.status
                        WHEN 'notpayment' THEN '未付款'
                        WHEN 'finished' THEN '已付款'
                        WHEN 'refunded' THEN '已退款'
                        else '待定状态'
                        end  as status,adminschedules.start_place,adminschedules.end_place,adminorder.created_at"))
                    ->leftJoin('adminschedules','adminorder.schedules_id','=','adminschedules.id')
                    ->where('order_num',$order_num)
                    ->first();
    }

    public static function cancelOrder($openid,$order_num){
        $order = Order::where('order_num',$order_num)->first();
        $user = User::select(DB::raw('openid'))->where('id',$order->user_id)->first();
        if($user->openid != $openid){
            return false;
        }
        $order->deleted_at =date('Y-m-d H:i:s');
        if($order->save()){
            return true;
        }else{
            return false;
        }
    }
}
