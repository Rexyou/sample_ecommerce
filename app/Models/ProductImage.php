<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;

    protected $guarded= [];
    protected $table= "product_images";

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createImage($request)
    {

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
        $exist = $this->where([ 'product_id'=> $request['product_id'], 'display_order'=> $request['display_order'] ])->exists();
        $data = false;
        if($exist){
            $data = true;
        }

        return successResponse($data);
    }
}
