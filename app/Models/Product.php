<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TYPE_NEW=1;
    const TYPE_PREORDER=2;
    const TYPE_SECONDHAND=3;
    const TYPE_FLAW=4;
    const TYPE_SALE=5;

    const SELLING_STATUS_PREORDER=0;
    const SELLING_STATUS_IN_STOCK=1;
    const SELLING_STATUS_LOW_STOCK=2;
    const SELLING_STATUS_OUT_STOCK=3;

    const STATUS_INIATE=0;
    const STATUS_ACTIVE=1;
    const STATUS_INACTIVE=2;

    public function brand()
    {
        return $this->hasOne(Brand::class);
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class)
                    ->where([ 'status'=> ProductImage::STATUS_ACTIVE ])
                    ->select('id', 'product_id', 'image_url', 'display_order')
                    ->orderBy('display_order');
    }

    public function product_options()
    {
        return $this->hasMany(ProductOption::class)->orderByRaw("category ASC, display_order ASC");;
    }
    
    public function product_option_details()
    {
        return $this->hasMany(ProductOptionDetail::class);
    }

    public function sizing() :Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }

    public function createProduct($request)
    {

        $code_name = substr(str_shuffle(Str::random(20).time()),0,16);
        $request['code_name'] = $code_name;

        $exist_record = $this->checkDisplayOrder($request);
        if($exist_record['data']){
            return errorResponse("", "display_order_exist", Response::HTTP_FOUND);
        }

        $creation = $this->create($request);
        if(!$creation){
            return errorResponse("", "creation_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse($creation);
    }

    public function checkDisplayOrder($request)
    {
        $exist = $this->where([ 'brand_id'=> $request['brand_id'], 'display_order'=> $request['display_order'] ])->exists();
        $data = false;
        if($exist){
            $data = true;
        }

        return successResponse($data);
    }

    public function checkProductExists($request)
    {
        $data = $this->where([ 'id'=> $request['id'] ])->first();
        if(!$data){
            return errorResponse("", "product_not_found", Response::HTTP_NOT_FOUND);
        }

        return successResponse($data);
    }

    public function filterProductOptions()
    {
        $product_options = ProductOption::where([ 'product_id'=> $this->id ])->get();
        $data = [];
        if(isset($product_options) && !empty($product_options)){
            foreach($product_options as $option){
                if(!isset($data[$option['category']])){
                    $data[$option['category']] = [];
                }

                $data[$option['category']][$option['id']] = $option['name'];
            }
        }

        $this->product_options = $data;

        return $this;
    }

    public function getProduct($product_id)
    {
        $data = $this->with('product_images', 'product_option_details')->where([ 'id'=> $product_id ])->first();
        if(!$data){
            return errorResponse("", "product_not_found", Response::HTTP_NOT_FOUND); 
        }

        $data = $data->filterProductOptions();

        return successResponse($data);
    }
}
