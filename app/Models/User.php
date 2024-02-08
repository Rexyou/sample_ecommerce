<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];
    protected $hidden = [ 'password' ];

    const TYPE_USER = 1;
    const TYPE_ADMIN = 2;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_SUSPENDED = 3;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function register($request)
    {

        $request['type'] = $this::TYPE_USER;
        $request['password'] = bcrypt($request['password']);

        $creation = $this->create($request);
        if(!$creation){
            return errorResponse("", "creation_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse();
    }

    public function login($request)
    {
        $exist = $this->where(function($query)use($request){
                    $query->where([ 'username'=> $request['login'] ])
                    ->orWhere([ 'email'=> $request['login'] ])
                    ->orWhere([ 'phone'=> $request['login'] ]);
                })->where([ 'status'=> $this::STATUS_ACTIVE ])->exists();

        if(!$exist){
            return errorResponse("", "user_or_password_incorrect", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $login_data = $request['login'];
        unset($request['login']);

        if(preg_match("/^[\w\d]{8,12}$/", $login_data)){
            $request['username'] = $login_data;
        }

        if(filter_var($login_data, FILTER_VALIDATE_EMAIL)){
            $request['email'] = $login_data;
        }

        if(preg_match("/\+[0-9]{10,14}$/", $login_data)){
            $request['phone'] = $login_data;
        }

        $token = Auth::attempt($request);
        if(!$token){
            return errorResponse("", "user_or_password_incorrect", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $user = Auth::user();
        $user->token = $token;

        return successResponse($user);
    }

}
