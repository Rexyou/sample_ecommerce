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
}
