<?php
namespace App\Services;

use App\Models\Brand;
use App\Models\PageSetting;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidateBrandAttribute;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ValidatePageSettingUpdate;
use App\Http\Requests\ValidatePageSettingAttribute;

class AdminService{

    public function __construct(PageSetting $pageSetting, Brand $brand){
        $this->pageSetting = $pageSetting;
        $this->brand = $brand;
    }

    public function createComponent($request)
    {

        $validation = new ValidatePageSettingAttribute;
        $validated = Validator::make($request->all(), $validation->rules());

        if($validated->fails()){
            return errorResponse("", $validated->messages(), 422);
        }

        $filter_list = $validated->validated();

        return $this->pageSetting->createComponent($filter_list);

    }

    public function updateComponent($request)
    {

        $request->merge([ 'id'=> $request->id ]);

        $validation = new ValidatePageSettingUpdate;
        $validated = Validator::make($request->all(), $validation->rules());

        if($validated->fails()){
            return errorResponse("", $validated->messages(), 422);
        }

        $filter_list = $validated->validated();

        return $this->pageSetting->updateComponent($filter_list);

    }

    public function createBrand($request)
    {
        $validation = new ValidateBrandAttribute;
        $validated = Validator::make($request->all(), $validation->rules());

        if($validated->fails()){
            return errorResponse("", $validated->messages(), 422);
        }

        $filter_list = $validated->validated();

        $checker_brand = $this->brand->whereRaw("name LIKE '%".$filter_list['name']."%'")->first();
        if($checker_brand){
            return errorResponse("", "name_exists", Response::HTTP_FOUND);
        }

        if(isset($filter_list['main_brand']) && !empty($filter_list['main_brand'])){
            $checker_main_brand = $this->brand->where([ 'id'=> $filter_list['main_brand'] ])->first();
            if(!$checker_main_brand){
                return errorResponse("", "main_brand_not_exist", Response::HTTP_NOT_FOUND);
            }
        }

        return $this->brand->createBrand($filter_list);
    }

}