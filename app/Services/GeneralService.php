<?php
namespace App\Services;

use App\Models\Brand;
use App\Models\PageSetting;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GeneralService{

    public function __construct(PageSetting $pageSetting, Brand $brand){
        $this->pageSetting = $pageSetting;
        $this->brand = $brand;
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

}