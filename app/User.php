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

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    protected $table = 'users';

    public static function saveUser($openid,$nickname,$img_url,$sex,$city){
        $openid_user = User::where('openid',$openid)->first();
        if($openid_user){
            $openid_user->openid = $openid;
            $openid_user->nickname = $nickname;
            $openid_user->img_url = $img_url;
            $openid_user->sex = $sex;
            $openid_user->city = $city;
            $openid_user->created_at = date('Y-m-d H:i:s');
            if($openid_user->save()){
                return 200;
            }else{
                return 403;
            }
        }else{
            $user = new User;
            $user->openid = $openid;
            $user->nickname = $nickname;
            $user->img_url = $img_url;
            $user->sex = $sex;
            $user->city = $city;
            if($user->save()){
                return 200;
            }else{
                return 403;
            }
        }

    }
}
