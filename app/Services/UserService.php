<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ValidateUserLogin;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidateUserAttribute;
use Symfony\Component\HttpFoundation\Response;

class UserService{

    public function __construct(User $user){
        $this->user = $user;
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

}