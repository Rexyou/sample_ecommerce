<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function addresses() :Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }

    public function preferences() :Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }

    public function updateProfile($request, $data)
    {
        DB::beginTransaction();

        try {

            $updation = $data->update($request);
            if(!$updation){
                return errorResponse("", "update_profile_failure", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            DB::commit();
            return successResponse();

        } catch (\Exception $e) {

            DB::rollback();
            return errorMessageHandler($e);

        }
    }
}
