<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $result = $this->userService->register($request);
        return finalResponse($result);
    }

    public function login(Request $request)
    {
        $result = $this->userService->login($request);
        return finalResponse($result);
    }

    public function logout(Request $request)
    {
        $result = $this->userService->logout($request);
        return finalResponse($result);
    }
}
