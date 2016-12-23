<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Http\Controllers\Controller;
use App\Paper;
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
}
