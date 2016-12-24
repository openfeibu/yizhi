<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Controllers\Controller;
use App\Schedules;
use DB;

class SchedulesController extends Controller
{
	public function getSchedulesAll(Request $request){
        if($this->checkOpenid($request) != 200){
            return $this->checkOpenid($request);
        }
		$getSchedulesAll  = Schedules::getSchedulesAll();
		return [
            'code'=>200,
            'detail'=>"请求成功",
            'data' => $getSchedulesAll
        ];
	}

    public function getSchedulesList(Request $request){
        $rules = [
            'openid' => 'required',
            'start' => 'required',
            'end' => 'required',
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
        $getSchedulesList  = Schedules::getSchedulesList($request->start,$request->end,$time);
        foreach ($getSchedulesList as $key => $list) {
            if(empty($list['overplus'])){
                $list['overplus'] = $list['seat_number'];
            }
            if(!empty($list['time_start']) && !empty($list['time_end'])){
                if(strtotime($list['time_start'])<=strtotime($time) && strtotime($list['time_end'])>=strtotime($time)){}else{
                    unset($getSchedulesList[$key]);
                }
            }elseif(empty($list['time_start']) && !empty($list['time_end'])){
                if(strtotime($list['time_end'])<=strtotime($time)){}else{
                    unset($getSchedulesList[$key]);
                }
            }elseif(!empty($list['time_start']) && empty($list['time_end'])){
                if(strtotime($list['time_start'])>=strtotime($time)){}else{
                    unset($getSchedulesList[$key]);
                }
            }
            unset($list['time_start']);
            unset($list['time_end']);
        }
        return [
            'code'=>200,
            'detail'=>"请求成功",
            'data' => $getSchedulesList
        ];
    }

    public function getSchedulesOne(Request $request){
        $rules = [
            'openid' => 'required',
            'car_id' => 'required',
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

        $getSchedulesOne  = Schedules::getSchedulesOne($request->car_id,$time);
        $getSchedulesOne['time'] = $time." ".$getSchedulesOne['time'];
        if(empty($getSchedulesOne['overplus'])){
            $getSchedulesOne['overplus'] = $getSchedulesOne['seat_number'];
        }
        return [
            'code'=>200,
            'detail'=>"请求成功",
            'data' => $getSchedulesOne
        ];
    }

}
