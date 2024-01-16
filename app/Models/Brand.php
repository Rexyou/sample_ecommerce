<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    public function socialPlatform(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value),
            set: fn (array $value) => json_encode($value)
        );
    }

    public function createBrand($request){

        $checker = $this->checkNameExists($request);
        if($checker['data']){
            return errorResponse("", "name_exist", Response::HTTP_FOUND);
        }

        $creation = $this->create($request);
        if(!$creation){
            return errorResponse("", "creation_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return successResponse();
    }

    public function checkNameExists($request)
    {
        $exists = $this->whereRaw("name LIKE '%".$request['name']."%'")->exists();
        $data=false;
        if($exists){
            $data=true;
        }

        return successResponse($data);
    }

    public function getBrandsList($request){

        $data = $this->where([ 'status'=> $this::STATUS_ACTIVE ])->orderBy('name')->get();

        $new_data = [];
        if($data->isNotEmpty()){
            
            foreach($data as $item){
                $name_array = str_split($item->name);

                $key = 0;
                $name = $name_array[$key];

                $current_alpha = strtolower($name);
                if(!isset($new_data[$current_alpha])){
                    $new_data[$current_alpha]= [];
                }

                $new_data[$current_alpha][] = $item;
            }

        }

        ksort($new_data);

        return successResponse($new_data);
    }
}
