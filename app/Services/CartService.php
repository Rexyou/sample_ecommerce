<?php
namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidateCartAttribute;
use Symfony\Component\HttpFoundation\Response;

class CartService{

    public function __construct(Cart $cart){
        $this->cart = $cart;
    }

    public function addCart($request)
    {
        $validation = new ValidateCartAttribute;
        $validator = Validator::make($request->all(), $validation->rules());

        if($validator->fails()){
            return errorResponse("", $validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validator->validated();

        // Checker for the product and product option detail
        $checker = $this->cart->checkProdutExist($request, true);
        if(!$checker['status']){
            return $checker;
        }

        return $this->cart->createCart($filter_list);
    }

}