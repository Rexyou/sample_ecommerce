<?php
namespace App\Services;

use App\Models\Brand;
use App\Models\Product;
use App\Models\PageSetting;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GeneralService{

    public function __construct(PageSetting $pageSetting, Brand $brand, Product $product){
        $this->pageSetting = $pageSetting;
        $this->brand = $brand;
        $this->product = $product;
    }

    public function getComponentList($request)
    {

        $validation = [
            'page_name'=> 'required|between:1,255|regex:/^(\w)+$/',
            'component'=> 'required|between:1,255|regex:/^(\w)+$/',
            'order'=> 'sometimes|string|in:asc,desc',
        ];
        $validated = Validator::make($request->all(), $validation);

        if($validated->fails()){
            return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validated->validated();

        return $this->pageSetting->getComponentList($filter_list);
    }

    public function getBrandsList($request)
    {
        return $this->brand->getBrandsList($request);
    }

    public function getBrand($request)
    {

        $request->merge([ 'id'=> $request->id ]);
        
        $validation = [
            'id'=> 'required|integer'
        ];

        $validated = Validator::make($request->all(), $validation);
        if($validated->fails()){
            return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validated->validated();

        return $this->brand->getBrand($filter_list);
    }

    public function getProduct($request)
    {
        return $this->product->getProduct($request->id);
    }

    public function getBrandProducts($request)
    {

        $request->merge([ 'id'=> $request->id ]);

        $checker = $this->brand->getBrand($request);
        if(!$checker['status']){
            return $checker;
        }

        $request->merge([ 'id'=> [ $request->id ] ]);
        
        $validation = [
            'id'=> 'required|array',
            'search_input'=> 'sometimes|string',
            'type'=> 'sometimes|array',
            'selling_status'=> 'sometimes|array',
            'price_min'=> 'sometimes|numeric',
            'price_max'=> 'sometimes|numeric',
            'sorting'=> 'sometimes|nullable|string|regex:/^[\w\_]+$/',
            'paginate'=> 'sometimes|integer',
        ];

        $validated = Validator::make($request->all(), $validation);
        if($validated->fails()){
            return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validated->validated();

        return $this->product->getProductList($filter_list);
    }

    public function getSearchProductList($request, $suggestion)
    {

        if(gettype($request->id) != "array"){
            $request->merge([ 'id'=> $request->id ]);

            $checker = $this->brand->getBrand($request);
            if(!$checker['status']){
                return $checker;
            }
    
            $request->merge([ 'id'=> [ $request->id ] ]);
        }

        $validation = [
            'id'=> 'required|array',
            'search_input'=> 'sometimes|nullable|string',
            'type'=> 'sometimes|array',
            'selling_status'=> 'sometimes|array',
            'price_min'=> 'sometimes|numeric',
            'price_max'=> 'sometimes|numeric',
            'sorting'=> 'sometimes|nullable|string|regex:/^[\w\_]+$/',
            'paginate'=> 'sometimes|integer',
        ];

        $validated = Validator::make($request->all(), $validation);
        if($validated->fails()){
            return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validated->validated();

        return $this->product->getSearchProductList($filter_list, $suggestion);
    }

}