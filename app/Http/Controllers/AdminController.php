<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminService;

class AdminController extends Controller
{

    public function __construct(AdminService $adminService){
        $this->adminService = $adminService;
    }

    public function createComponent(Request $request)
    {
        $result = $this->adminService->createComponent($request);
        return finalResponse($result);
    }

    public function updateComponent(Request $request)
    {
        $result = $this->adminService->updateComponent($request);
        return finalResponse($result);
    }

    public function getComponentList(Request $request)
    {
        $result = $this->adminService->getComponentList($request);
        return finalResponse($result);
    }

    public function createBrand(Request $request)
    {
        $result = $this->adminService->createBrand($request);
        return finalResponse($result);
    }

    public function createProduct(Request $request)
    {
        $result = $this->adminService->createProduct($request);
        return finalResponse($result);
    }
}
