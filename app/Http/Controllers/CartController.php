<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;

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
}
