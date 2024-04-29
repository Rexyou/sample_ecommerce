<?php
namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidateCartAttribute;
use Symfony\Component\HttpFoundation\Response;

class CartService{

    public function __construct(Cart $cart){
        $this->cart = $cart;
        $this->paginate = 15;
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
        $checker = $this->cart->checkProductExist($request, true);
        if(!$checker['status']){
            return $checker;
        }

        DB::beginTransaction();
    
        try {

            $creation = $this->cart->create($filter_list);
            if(!$creation){
                return errorResponse("", "creation_cart_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            DB::commit();
            return successResponse();

        } catch (\Exception $e) {

            DB::rollback();
            return errorMessageHandler($e);

        }

    }

    public function getCartList($request)
    {
        $user = Auth::user();
        $list = $this->cart->with(['product'=> function($query){
            $query->with('product_option_details');
        }])->where([ 'user_id'=> $user->id, 'status'=> Cart::STATUS_NEW ]);

        $paginate = $this->paginate;
        if(isset($request->paginate) && !empty($request->paginate)){
            $paginate = $request->paginate;
        }
        $list = $list->orderByDesc('id')->paginate($paginate);

        foreach($list->items() as &$item)
        {
            $item->product->filterProductOptions();
        }

        return successResponse($list);
    }

}