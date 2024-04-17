<?php
namespace App\Services;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ValidateUserLogin;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidateUserAttribute;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ValidateProfileAttribute;
use App\Http\Requests\ValidateUserRegisterAttribute;

class UserService{

    public function __construct(User $user, Profile $profile){
        $this->user = $user;
        $this->profile = $profile;
    }

    public function register($request)
    {
        $validation = new ValidateUserRegisterAttribute;
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

    public function updateUser($request)
    {
        $validation = new ValidateUserAttribute;
        $validator = Validator::make($request->all(), $validation->rules());

        if($validator->fails()){
            return errorResponse("", $validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validator->validated();

        $preferences = [];
        if(isset($filter_list['preferences']) && count($filter_list['preferences']) > 0){
            $preferences = $filter_list['preferences'];
            unset($filter_list['preferences']);
        }

        if(isset($filter_list['current_password']) && isset($filter_list['password'])){
            // Compare password
            $hashedtext = Auth::user()->password;
            $checker = password_verify($filter_list['current_password'], $hashedtext);
            if(!$checker){
                return errorResponse("", "current_password_incorrect", Response::HTTP_INTERNAL_SERVER_ERROR); 
            }

            unset($filter_list['password_confirmation'], $filter_list['current_password']);

            $filter_list['password'] = bcrypt($filter_list['password']);
        }
        
        DB::beginTransaction();

        try {

            $user = Auth::user();

            if(count($filter_list) > 0){
                $update = $user->update($filter_list);
                if(!$update){
                    return errorResponse("", "update_user_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            if(count($preferences) > 0){
                $update_profile = $user->profile->update(compact('preferences'));
                if(!$update_profile){
                    return errorResponse("", "update_user_profile_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            
            DB::commit();
            return $this->getProfile($request);

        } catch (\Exception $e) {

            DB::rollback();
            return errorMessageHandler($e);

        }
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