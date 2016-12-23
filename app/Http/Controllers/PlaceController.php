<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Controllers\Controller;
use App\Place;
use DB;

class PlaceController extends Controller
{
	public function getMaxPlace(Request $request){
        if($this->checkOpenid($request) != 200){
            return $this->checkOpenid($request);
        }
		$getMaxPlace  = Place::getMaxPlace();
		return [
            'code'=>200,
            'detail'=>"请求成功",
            'data' => $getMaxPlace
        ];
	}

	public function getNextPlace(Request $request){
        if($this->checkOpenid($request) != 200){
            return $this->checkOpenid($request);
        }
        $getNextPlace  = Place::getNextPlace($request->id);
        return [
            'code'=>200,
            'detail'=>"请求成功",
            'data' => $getNextPlace
        ];
	}
}
