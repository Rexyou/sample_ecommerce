<?php
namespace App\Services;

use App\Models\User;
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
        $validated = Validator::make($request->all(), $validation->rules());

        if($validated->fails()){
            return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validated->validated();

        return $this->user->register($filter_list);
    }

}