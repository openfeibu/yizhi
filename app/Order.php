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
use Session;
use DB;

class Order extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    protected $table = 'adminorder';

    public static function bookOrder($openid,$car_id,$mobile,$username,$number,$time,$start_details,$end_details){
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

        return "YZ".date('YmdHis').$user->id;
    }
}
