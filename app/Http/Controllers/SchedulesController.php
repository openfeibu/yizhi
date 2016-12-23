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
        if($this->checkOpenid($request) != 200){
            return $this->checkOpenid($request);
        }
        $getSchedulesList  = Schedules::getSchedulesList($request->start,$request->end,$request->time);
        foreach ($getSchedulesList as $key => $list) {
            if(empty($list['overplus'])){
                $list['overplus'] = $list['seat_number'];
            }
        }
        return [
            'code'=>200,
            'detail'=>"请求成功",
            'data' => $getSchedulesList
        ];
    }
}
