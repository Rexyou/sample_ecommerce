<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOption extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "product_options";

    public function createOption($request)
    {
        $exist_name_record = $this->checkRecordExist($request);
        if($exist_name_record['data']){
            return errorResponse("", "category_name_exist", Response::HTTP_FOUND);
        }

        $exist_record = $this->checkDisplayOrder($request);
        if($exist_record['data']){
            return errorResponse("", "display_order_exist", Response::HTTP_FOUND);
        }

        $creation = $this->firstOrCreate([ 'category'=> $request['category'], 'name'=> $request['name'] ], $request);
        if(!$creation){
            return errorResponse("", "creation_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse();
    }

    public function checkDisplayOrder($request)
    {
        $exist = $this->where([ 'product_id'=> $request['product_id'], 'category'=> $request['category'], 'display_order'=> $request['display_order'] ])->exists();
        $data = false;
        if($exist){
            $data = true;
        }

        return successResponse($data);
    }

    public function checkRecordExist($request)
    {
        $exist = $this->where([ 'product_id'=> $request['product_id'], 'category'=> $request['category'] ])->whereRaw("name LIKE '%".$request['name']."%'")->exists();
        $data = false;
        if($exist){
            $data = true;
        }

        return successResponse($data);
    }

    public function getCategory($product_id)
    {
        $data = $this->where([ 'product_id'=> $product_id ])->groupBy('category')->pluck('category')->toArray();

        return successResponse($data);
    }
}
