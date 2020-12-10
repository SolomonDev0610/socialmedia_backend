<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table = 'users';
    protected $fillable = [
        'firstname', 'lastname', 'email', 'phone', 'password','plain_password','total_points'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public static function authenticate($jwt) {
        $user = JWTAuth::setToken($jwt)->toUser();

        if (!$user) {
            throw new JWTException('invalid_token');
        }

        return $user;
    }

    public static function add_client($request){

        $user = new User();
        $user->email = $request['email'];
        $user->image = $request['image'];
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->phone = $request['phone'];
        $user->address = $request['address'];
        $user->timezone = $request['timezone'];
        $user->country = $request['country'];
        $user->state = $request['state'];

        $user->save();
        return ['success' => true];
    }

    public static function update_client($request){
        $user = User::find($request['id']);

        $user->email = $request['email'];
        $user->image = $request['image'];
        $user->firstname = $request['firstname'];
        $user->lastname = $request['lastname'];
        $user->phone = $request['phone'];
        $user->address = $request['address'];
        $user->country = $request['country'];
        $user->state = $request['state'];

        $user->save();
        return ['success' => true];
    }
    public static function delete_client( $id ){
        self::where('id', $id)->delete();
        return true;
    }

    public static function update_profile($request, $id){
        //------- save email -------
        $user = User::find($id);
        $user->firstname = $request['firstname'];
        $user->lastname = $request['lastname'];
        $user->email = $request['email'];
        $user->phone_number = $request['phone_number'];
        $user->state = $request['state'];
        $user->country = $request['country'];

        $user->plain_password = $request['password'];
        $user->password = Hash::make($request['password']);
        $user->save();

        return ['success' => true];
    }
}
