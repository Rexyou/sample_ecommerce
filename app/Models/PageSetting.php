<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageSetting extends Model
{
    use HasFactory;

    protected $table = "page_setting";
    protected $guarded = [];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    public function createComponent($request){

        $exist_record = $this->checkComponentExist($request);
        if($exist_record['data']){
            return errorResponse("", "display_order_exist", Response::HTTP_FOUND);
        }

        $creation = $this->create($request);
        if(!$creation){
            return errorResponse("", "creation_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse();
    }

    public function checkComponentExist($request){

        $exist = $this->where([ 'page_name'=> $request['page_name'], 'component'=> $request['component'], 'display_order'=> $request['display_order'] ])->exists();
        $data = false;
        if($exist){
            $data = true;
        }

        return successResponse($data);
    }

    public function updateComponent($request)
    {

        $exist_data = $this->where([ 'id'=> $request['id'] ])->first();
        if(!$exist_data){
            return errorResponse("", "component_not_found", Response::HTTP_NOT_FOUND);
        }

        if(isset($request['display_order']) && $exist_data->display_order != $request['display_order']){

            $page_name = $request['page_name'] ?? $exist_data['page_name'];
            $component = $request['component']  ?? $exist_data['component'];
            $display_order = $request['display_order']; 

            $checker = $this->checkComponentExist(compact('page_name', 'component', 'display_order'));
            if($checker['data']){
                return errorResponse("", "display_order_exist", Response::HTTP_FOUND);
            }
        }

        unset($request['id']);
        $updation = $exist_data->update($request);
        if(!$updation){
            return errorResponse("", 'updation_failure', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse();
    }

    public function getComponentList($request)
    {
        $component = $this;
        $order = "desc";

        if(isset($request['page_name'])){
            $component = $component->where([ 'page_name'=> $request['page_name'] ]);
        }

        if(isset($request['component'])){
            $component = $component->where([ 'component'=> $request['component'] ]);
        }

        if(isset($request['order'])){
            $order = $request['order'];
        }

        $component = $component->where([ 'status'=> $this::STATUS_ACTIVE ]);

        if(preg_match('(home)',$request['page_name'])){
            $component = $component->orderBy('display_order', $order)->paginate(10);
        }
        else {
            $component = $component->first();
        }
        

        return successResponse($component);
    }
}
