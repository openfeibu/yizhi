<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Controllers\Controller;
use App\ServicePhone;
use DB;

class ServicePhoneController extends Controller
{
	public function getServicePhone(Request $request){
		if($this->checkOpenid($request) != 200){
            return $this->checkOpenid($request);
        }
		$ServicePhone =  ServicePhone::select(DB::raw('mobile'))->orderBy('id','desc')->first();
		return [
			'code'=>200,
			'detail'=>"请求成功",
			'data' => $ServicePhone
		];
	}
}
