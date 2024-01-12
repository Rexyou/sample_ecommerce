<?php
namespace App\Services;

use App\Models\PageSetting;
use Illuminate\Support\Facades\Validator;

class GeneralService{

    public function __construct(PageSetting $pageSetting){
        $this->pageSetting = $pageSetting;
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
            return errorResponse("", $validated->messages(), 422);
        }

        $filter_list = $validated->validated();

        return $this->pageSetting->getComponentList($filter_list);
    }

}