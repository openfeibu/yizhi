<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Validator;
use App\User;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function validateParameter($request,$rules)
	{
		$validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
    		return [
                'code'=>110,
                'detail'=>$validator->errors()->first(),
            ];
        } else {
        	return 200;
        }
	}
    public function checkOpenid($request){
        $rule = [
            'openid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return [
                'code'=>110,
                'detail'=>$validator->errors()->first(),
            ];
        } else {
            $openid_user = User::where('openid',$request->openid)->first();
            if($openid_user){
                return 200;
            }else{
                return [
                    'code'=>404,
                    'detail'=>"openid无效",
                ];
            }
        }
        
    }
}
