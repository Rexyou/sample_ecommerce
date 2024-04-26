<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Searchable;

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

    const PAGINATE=15;

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'code_name' => $this->code_name,
            'description' => $this->description,
        ];
    }

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with('brand', 'product_images_filter');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function product_images_filter()
    {
        return $this->product_images()->where([ 'status'=> ProductImage::STATUS_ACTIVE ])
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
        // Avoid call relationships data
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
        $data = $data->getProductPrice();

        foreach($data->product_option_details as $option_detail)
        {
            $data->total_quantity += $option_detail->quantity;
        }

        return successResponse($data);
    }

    public function getProductPrice()
    {
        $product_option_details = $this->product_option_details()->orderByDesc('original_price')->get([ 'original_price', 'member_price', 'sale_price', 'sale_member_price' ])->toArray();
        if(count($product_option_details) == 1){
            $this->price_range = $product_option_details[0]['original_price'];
            $this->member_price = $product_option_details[0]['member_price'];
            $this->sale_price = $product_option_details[0]['sale_price'];
            $this->sale_member_price = $product_option_details[0]['sale_member_price'];
        }
        else {
            $this->price_min = end($product_option_details);
            $this->price_max = reset($product_option_details);
            $this->price_range = $this->price_min['original_price']." - ".$this->price_max['original_price'];

            $this->member_price = "";
            if($this->price_min['member_price'] != 0 && $this->price_max['member_price'] != 0){
                $this->member_price = $this->price_min['member_price']." - ".$this->price_max['member_price'];
            }

            $this->sale_price = "";
            if($this->price_min['sale_price'] != 0 && $this->price_max['sale_price'] != 0){
                $this->sale_price = $this->price_min['sale_price']." - ".$this->price_max['sale_price'];
            }

            $this->sale_member_price = "";
            if($this->price_min['sale_member_price'] != 0 && $this->price_max['sale_member_price'] != 0){
                $this->sale_member_price = $this->price_min['sale_member_price']." - ".$this->price_max['sale_member_price'];
            }

        }

        return $this;
    }

    public function getProductList($request)
    {
        $data = $this->with(['brand'=> function($query){
            $query->select('id','name');
        }, 'product_images_filter'])->where([ 'status'=> $this::STATUS_ACTIVE ]);

        if(isset($request['search_input']) && !empty($request['search_input'])){
            $data = $data->where(function($query) use($request){
                $query->whereRaw("name LIKE '%".$request['search_input']."%'")
                        ->orWhereRaw("code_name LIKE '%".$request['search_input']."%'")
                        ->orWhereRaw("description LIKE '%".$request['search_input']."%'");
            });
        }

        if(isset($request['id']) && count($request['id']) > 0){
            $data = $data->whereIn('brand_id', $request['id']);
        }

        if(isset($request['type']) && count($request['type']) > 0){
            $data = $data->whereIn('type', $request['type']);
        }

        if(isset($request['selling_status']) && count($request['selling_status']) > 0){
            $data = $data->whereIn('selling_status', $request['selling_status']);
        }

        if(isset($request['price_min']) && !empty($request['price_min'])){
            $data = $data->whereHas('product_option_details', function($query)use($request){
                $query->whereRaw("original_price >= ".$request['price_min']);
            });
        }

        if(isset($request['price_max']) && !empty($request['price_max']) > 0){
            $data = $data->whereHas('product_option_details', function($query)use($request){
                $query->whereRaw("original_price <= ".$request['price_max']);
            });
        }

        if(isset($request['sorting']) && !empty($request['sorting'])){
            switch ($request['sorting']) {
                case 'created_asc':
                    $data = $data->orderBy("created_at");
                    break;

                case 'created_desc':
                    $data = $data->orderByDesc("created_at");
                    break;
                
                default:
                    $data = $data->orderByDesc("display_order");
                    break;
            }
        }
        else {
            $data = $data->orderByDesc("display_order");
        }

        $paginate = $this::PAGINATE;
        if(isset($request['paginate']) && !empty($request['paginate'])){
            $paginate = $request['paginate'];
        }
        $data = $data->paginate($paginate);

        foreach($data->items() as $item){
            $item = $item->getProductPrice();
        }

        return successResponse($data);
    }

    public function getSearchProductList($request, $suggestion=true)
    {
        $search = "";
        if(isset($request['search_input']) && !empty($request['search_input'])){
            $search = $request['search_input'];
        }

        $data = $this->search($search)->query(function(Builder $query){ $query->with('brand'); })->where('status', $this::STATUS_ACTIVE);
        // if($suggestion){
        //     $data = $data->query(function($query){
        //         $query->select('id', 'name', 'code_name');
        //     });
        // }
        // else {
            // $data = $data->query(function($query){
                // $query->with(['brand'=> function($query){
                //     $query->select('id','name');
                // }, 'product_images_filter']);
                // $query->with('brand');
            // });
        // }

        if(isset($request['id']) && count($request['id']) > 0){
            $data = $data->whereIn('brand_id', $request['id']);
        }

        if(isset($request['type']) && count($request['type']) > 0){
            $data = $data->whereIn('type', $request['type']);
        }

        if(isset($request['selling_status']) && count($request['selling_status']) > 0){
            $data = $data->whereIn('selling_status', $request['selling_status']);
        }

        if(isset($request['price_min']) && !empty($request['price_min'])){
            $data = $data->whereHas('product_option_details', function($query)use($request){
                $query->whereRaw("original_price >= ".$request['price_min']);
            });
        }

        if(isset($request['price_max']) && !empty($request['price_max']) > 0){
            $data = $data->whereHas('product_option_details', function($query)use($request){
                $query->whereRaw("original_price <= ".$request['price_max']);
            });
        }

        if(isset($request['sorting']) && !empty($request['sorting'])){
            switch ($request['sorting']) {
                case 'created_asc':
                    $data = $data->query(function($query){
                                $query->orderBy("created_at");
                            });
                    break;

                case 'created_desc':
                    $data = $data->query(function($query){
                        $query->orderByDesc("created_at");
                    });
                    break;
                
                default:
                    $data = $data->query(function($query){
                        $query->orderByDesc("display_order");
                    });
                    break;
            }
        }
        else {
            $data = $data->query(function($query){
                $query->orderByDesc("display_order");
            });
        }

        $paginate = $this::PAGINATE;
        if(isset($request['paginate']) && !empty($request['paginate'])){
            $paginate = $request['paginate'];
        }
        $data = $data->paginate($paginate);

        if(!$suggestion){
            foreach($data->items() as $item){
                $item = $item->getProductPrice();
            }
        }

        return successResponse($data);
    }
}
