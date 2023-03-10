<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Schedules;
use DB;
use Session;

class Schedules extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    protected $table = 'adminschedules';

    public static function getSchedulesAll(){
    	return Schedules::get();
    }
 
    public static function getSchedulesList($start , $end , $time){
        Session::set('time',$time);
		return Schedules::select(DB::raw('adminschedules.id as car_id,adminschedules.time,adminschedules.start_place,adminschedules.end_place,adminschedules.seat_number,adminschedules.price,overPlus.overplus,adminschedules.time_start,adminschedules.time_end'))
                        ->leftJoin('overPlus', function($join) {
                                $join->on('adminschedules.id', '=', 'overPlus.schedules_id')
                                     ->where('overPlus.time','=',Session::get('time'));
                        })
                        ->where('start_place', $start)
                        ->where('end_place', $end)
                        ->where('status', 1)
                        ->whereNull('deleted_at')
                        ->orderBy('adminschedules.time','asc')
					    ->get();
    }

    public static function getSchedulesOne($car_id , $time){
        Session::set('time',$time);
        return Schedules::select(DB::raw('adminschedules.id as car_id,adminschedules.time,adminschedules.start_place,adminschedules.end_place,adminschedules.seat_number,adminschedules.price,overPlus.overplus'))
                        ->leftJoin('overPlus', function($join) {
                                $join->on('adminschedules.id', '=', 'overPlus.schedules_id')
                                     ->where('overPlus.time','=',Session::get('time'));
                        })
                        ->where('adminschedules.id', $car_id)
                        ->where('status', 1)
                        ->whereNull('deleted_at')
                        ->first();
    }

}
