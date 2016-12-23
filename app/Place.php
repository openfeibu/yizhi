<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Place;
use DB;

class Place extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    protected $table = 'adminplace';

    public static function getMaxPlace(){
        return Place::select(DB::raw('place,id'))->where('level',1)->whereNull('deleted_at')->get();
    }

    public static function getNextPlace($id){
        return Place::select(DB::raw('place,id'))
                    ->where('place_id',$id)
                    ->whereNull('deleted_at')
                    ->get();
    }
 
}
