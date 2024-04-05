<?php
namespace App\Services;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ValidateUserLogin;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidateUserAttribute;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ValidateProfileAttribute;

class UserService{

    public function __construct(User $user, Profile $profile){
        $this->user = $user;
        $this->profile = $profile;
    }

    public function register($request)
    {
        $validation = new ValidateUserAttribute;
        $validator = Validator::make($request->all(), $validation->rules());

        if($validator->fails()){
            return errorResponse("", $validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validator->validated();

        return $this->user->register($filter_list);
    }

    public function login($request)
    {
        $validation = new ValidateUserLogin;
        $validator = Validator::make($request->all(), $validation->rules());

        if($validator->fails()){
            return errorResponse("", $validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validator->validated();

        return $this->user->login($filter_list);
    }

    public function logout($request)
    {
        Auth::logout();
        return successResponse();
    }

    public function updateProfile($request)
    {
        $validation = new ValidateProfileAttribute;
        $validator = Validator::make($request->all(), $validation->rules());

        if($validator->fails()){
            return errorResponse("", $validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY); 
        }

        $filter_list = $validator->validated();
        $profile = Auth::user()->profile;

        $result = $this->profile->updateProfile($filter_list, $profile);
        if($result['status']){
            return $this->getProfile($request);
        }

        return $result;
    }

    public function getProfile($request)
    {
        $user = Auth::user();
        $user->profile = $user->profile;

        if(!isset($user->profile)){
            return errorResponse("", $validator->messages(), Response::HTTP_INTERNAL_SERVER_ERROR);  
        }

        return successResponse($user);
    }

}