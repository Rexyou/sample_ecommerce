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
    const STATUS_DELETE = 4;

    protected $guarded = [];
    protected $table = "cart";

    public function checkProductExist($request)
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

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function product_option_details()
    {
        return $this->hasOne(ProductOptionDetail::class, 'id', 'product_option_detail_id');
    }
}
