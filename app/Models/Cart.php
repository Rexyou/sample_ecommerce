<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    const STATUS_NEW = 1;
    const STATUS_PENDING_PAYMENT = 2;
    const STATUS_PAID = 3;

    protected $guarded = [];
    protected $table = "cart";

    public function createCart($request)
    {
        
        DB::beginTransaction();

        try {

            $creation = $this->create($request);
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

    public function checkProdutExist($request)
    {

        $product_exist = Product::where([ 'id'=> $request['product_id'], 'status'=> Product::STATUS_ACTIVE ])->exists();
        if(!$product_exist){
            return errorResponse("", "product_not_found", Response::HTTP_NOT_FOUND);
        }

        $product_option_detail_exist = ProductOptionDetail::where([ 'id'=> $request['product_option_detail_id'], 'status'=> ProductOptionDetail::STATUS_ACTIVE ])->exists();
        if(!$product_option_detail_exist){
            return errorResponse("", "product_option_detail_not_found", Response::HTTP_NOT_FOUND);
        }

        return successResponse();
    }
}
