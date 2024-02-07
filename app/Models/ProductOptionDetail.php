<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOptionDetail extends Model
{
    use HasFactory;

    protected $table = "product_option_details";
    protected $guarded = [];

    const STATUS_ACTIVE= 1;
    const STATUS_INACTIVE= 2;

    public function options() :Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => !empty($value) ? json_encode($value) : NULL,
        );
    }

    public function getProductOptions($product_id)
    {
        $data = ProductOption::where([ 'product_id'=> $product_id, 'status'=> $this::STATUS_ACTIVE ])->select('id', 'category', 'name')->get();
        if($data->isEmpty()){
            $data = [];
        }
        else {
            $new_data = [];
            foreach($data as $value)
            {
                if(!isset($new_data[$value['category']])){
                    $new_data[$value['category']] = [];
                }

                $new_data[$value['category']][$value['id']] = $value['name'];
            }

            $data = $new_data;
        }

        return successResponse($data);
    }

    public function createOptionDetail($request)
    {
        $exist_detail_record = $this->checkRecordExist($request);
        if($exist_detail_record['data']){
            return errorResponse("", "option_detail_exist", Response::HTTP_FOUND);
        }

        $creation = $this->create($request);
        if(!$creation){
            return errorResponse("", "creation_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse();
    }

    public function checkRecordExist($request)
    {

        $data = false;
        $exist = $this->where([ 'product_id'=> $request['product_id'] ]);

        if(isset($request['options']) && !empty($request['options'])){
            foreach($request['options'] as $category => $id){
                $exist = $exist->whereJsonContains("options->$category", $id);
            }
        }

        $exist = $exist->exists();
        if($exist){
            $data = true;
        }

        return successResponse($data);
    }
}
