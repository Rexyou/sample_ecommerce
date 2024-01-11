<?php
namespace App\Services;

use App\Models\PageSetting;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidatePageSettingUpdate;
use App\Http\Requests\ValidatePageSettingAttribute;

class AdminService{

    public function __construct(PageSetting $pageSetting){
        $this->pageSetting = $pageSetting;
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

}