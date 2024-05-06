<?php
namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOptionDetail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ValidateCartUpdate;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidateCartAttribute;
use Symfony\Component\HttpFoundation\Response;

class CartService{

    public function __construct(Cart $cart, ProductOptionDetail $productOptionDetail){
        $this->cart = $cart;
        $this->paginate = 15;
        $this->productOptionDetail = $productOptionDetail;
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
            $query->with('product_images_filter');
        }, 'product_option_details'])->where([ 'user_id'=> $user->id, 'status'=> Cart::STATUS_NEW ]);

        $paginate = $this->paginate;
        if(isset($request->paginate) && !empty($request->paginate)){
            $paginate = $request->paginate;
        }
        $list = $list->orderByDesc('id')->paginate($paginate);

        foreach($list->items() as &$item)
        {
            $item->product->filterProductOptions();
            if($item->product->product_options != null){
                foreach($item->product_option_details->options as $key => &$value){
                    if(isset($item->product->product_options[$key][$value])){
                        $value = $item->product->product_options[$key][$value];
                    }
                }
            }
        }

        return successResponse($list);
    }

    public function checkCartDetail($id, $user_id=0)
    {
        $condition = [ 'id'=> $id ];
        if(!empty($user_id)){
            $condition['user_id'] = $user_id;
        }

        $data = $this->cart->with("product_option_details")->where($condition)->first();

        if(!$data){
            return errorResponse("", "cart_not_found", Response::HTTP_NOT_FOUND);
        }

        return successResponse($data);
    }

    public function adjustCartDetail($request)
    {
        $validation = new ValidateCartUpdate;
        $validator = Validator::make($request->all(), $validation->rules());

        if($validator->fails()){
            return errorResponse("", $validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY); 
        }

        $filter_list = $validator->validated();

        $user = Auth::user();
        $user_id = $user->id;

        $checker = $this->checkCartDetail($filter_list['id'], $user_id);
        if(!$checker['status']){
            return $checker;
        }

        $data = $checker['data'];
        unset($filter_list['id']);

        if(isset($filter_list['quantity']) && !empty($filter_list['quantity'])){
            $current_quantity = $data->product_option_details->quantity;
            $current_product_option_detail = $data->product_option_details;

            if(isset($filter_list['product_option_detail_id']) && !empty($filter_list['product_option_detail_id'])){
                $product_id = $data->product_id;
                $product_option_details = $this->productOptionDetail->where([ 'id'=> $filter_list['product_option_detail_id'], 'product_id'=> $product_id ])->first();
                if(!$product_option_details){
                    return errorResponse("", "cart_not_found", Response::HTTP_NOT_FOUND);
                }

                $current_quantity = $product_option_details->quantity;
                $current_product_option_detail = $product_option_details;
            }

            if($filter_list['quantity'] > $current_quantity){
                return errorResponse("", "cart_quantity_over_limit", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if($current_product_option_detail->member_price != 0){
                $filter_list['per_unit_price'] = $current_product_option_detail->member_price;
                $filter_list['total_price'] = $current_product_option_detail->member_price * $filter_list['quantity'];
            }

            if($current_product_option_detail->sale_member_price != 0){
                $filter_list['per_unit_price'] = $current_product_option_detail->sale_member_price;
                $filter_list['total_price'] = $current_product_option_detail->sale_member_price * $filter_list['quantity'];
            }

            DB::beginTransaction();

            try {
                
                $updation = $data->update($filter_list);
                if(!$updation){
                    return errorResponse("", "cart_update_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
                }
                
                DB::commit();
                return successResponse();

            } catch (\Exception $e) {

                DB::rollback();
                return errorMessageHandler($e);
    
            }

        }

        return successResponse();
    }

    public function removeCartItem($request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $checker = $this->checkCartDetail($request->id, $user_id);
        if(!$checker['status']){
            return $checker;
        }

        $data = $checker['data'];

        DB::beginTransaction();

        try {
            
            $updation = $data->update([ 'status'=> Cart::STATUS_DELETE ]);
            if(!$updation){
                return errorResponse("", "update_cart_status_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            DB::commit();
            return successResponse();

        } catch (\Exception $e) {

            DB::rollback();
            return errorMessageHandler($e);

        }

    }

}