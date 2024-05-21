<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addCart(Request $request)
    {
        $result = $this->cartService->AddCart($request);
        return finalResponse($result);
    }

    public function getCartList(Request $request)
    {
        $result = $this->cartService->getCartList($request);
        return finalResponse($result);
    }

    public function adjustCartDetail(Request $request)
    {
        $result = $this->cartService->adjustCartDetail($request);
        return finalResponse($result);
    }

    public function removeCartItem(Request $request)
    {
        $result = $this->cartService->removeCartItem($request);
        return finalResponse($result);
    }

    // public function batchGetCartDetail(Request $request)
    // {
    //     $validation = [ 'list'=> 'required|array|min:1' ];
    //     $validator = Validator::make($request->all(), $validation);

    //     if($validator->fails()){
    //         return finalResponse(errorResponse("", $validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY));
    //     }

    //     $filter_list = $validator->validated();
    //     $return_list = [];

    //     foreach($filter_list['list'] as $id){
    //         $result = $this->cartService->updateCurrentCart($id);
    //         $return_list[$id] = $result['data'];
    //     }

    //     return finalResponse(successResponse($return_list));
    // }

    public function encryptCartItem(Request $request)
    {
        $result = $this->cartService->transfromCartList("encrypt", $request);
        return finalResponse($result);
    }

    public function decryptCartKey(Request $request)
    {
        $result = $this->cartService->transfromCartList("decrypt", $request);
        return finalResponse($result);
    }
}
