<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeneralService;

class GeneralController extends Controller
{
    public function __construct(GeneralService $generalService){
        $this->generalService = $generalService;
    }

    public function getComponentList(Request $request)
    {
        $result = $this->generalService->getComponentList($request);
        return finalResponse($result);
    }

    public function getBrandsList(Request $request)
    {
        $result = $this->generalService->getBrandsList($request);
        return finalResponse($result);
    }

    public function getBrand(Request $request)
    {
        $result = $this->generalService->getBrand($request);
        return finalResponse($result);
    }

    public function getProduct(Request $request)
    {
        $result = $this->generalService->getProduct($request);
        return finalResponse($result);
    }

    public function getBrandProducts(Request $request)
    {
        $result = $this->generalService->getBrandProducts($request, false);
        return finalResponse($result);
    }

    public function getSearchProductList(Request $request)
    {
        $result = $this->generalService->getSearchProductList($request, true);
        return finalResponse($result);
    }
}
