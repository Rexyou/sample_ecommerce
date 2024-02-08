<?php
namespace App\Services;

use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\PageSetting;
use App\Models\ProductImage;
use App\Models\ProductOption;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOptionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidateBrandAttribute;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ValidateProductAttribute;
use App\Http\Requests\ValidatePageSettingUpdate;
use App\Http\Requests\ValidatePageSettingAttribute;
use App\Http\Requests\ValidateProductImageAttribute;
use App\Http\Requests\ValidateProductOptionAttribute;
use App\Http\Requests\ValidateProductOptionDetailAttribute;

class AdminService{

    public function __construct(PageSetting $pageSetting, Brand $brand, Product $product, ProductImage $product_images, ProductOption $product_options, ProductOptionDetail $product_option_details){
        $this->pageSetting = $pageSetting;
        $this->brand = $brand;
        $this->product = $product;
        $this->product_images = $product_images;
        $this->product_options = $product_options;
        $this->product_option_details = $product_option_details;
    }

    public function createComponent($request)
    {

        $validation = new ValidatePageSettingAttribute;
        $validated = Validator::make($request->all(), $validation->rules());

        if($validated->fails()){
            return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
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
            return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter_list = $validated->validated();

        return $this->pageSetting->updateComponent($filter_list);

    }

    public function createBrand($request)
    {
        $validation = new ValidateBrandAttribute;
        $validated = Validator::make($request->all(), $validation->rules());

        if($validated->fails()){
            return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
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

    public function createProduct($request)
    {

        DB::beginTransaction();

        try {

            $exists = $this->brand->getBrand([ 'id'=> $request['brand_id'] ]);
            if(!$exists['status']){
                return $exists;
            }

            // $user = Auth::user();
            // $user_id = $user->id;
            $user = User::where([ 'type'=> User::TYPE_ADMIN, 'status'=> User::STATUS_ACTIVE ])->first();
            $user_id = $user->id;

            $request->merge([ 'created_by'=> $user_id ]);
            $validation = new ValidateProductAttribute;
            $validated = Validator::make($request->all(), $validation->rules());
            
            if($validated->fails()){
                return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $filter_list = $validated->validated();

            $result = $this->product->createProduct($filter_list);
            
            DB::commit();

            return $result;

        } catch (\Exception $e) {

            DB::rollback();
            return errorMessageHandler($e);

        }
        
    }

    public function createProductImages($request)
    {

        DB::beginTransaction();

        try {
            
            // $user = Auth::user();
            // $user_id = $user->id;
            $user = User::where([ 'type'=> User::TYPE_ADMIN, 'status'=> User::STATUS_ACTIVE ])->first();
            $user_id = $user->id;

            // Checker
            $exists = $this->product->checkProductExists([ 'id'=> $request->product_id ]);
            if(!$exists['status']){
                return $exists;
            }

            // Create product image
            $product_images = $request->product_images;
            foreach($product_images as &$image){
                $image['product_id'] = $request->product_id;
                $image['created_by'] = $user_id;
            }

            $validation_images = new ValidateProductImageAttribute;
            $validated_images = Validator::make(compact('product_images'), $validation_images->rules());

            if($validated_images->fails()){
                return errorResponse("", $validated_images->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $filter_list_image = $validated_images->validated();

            foreach($filter_list_image['product_images'] as $images){
                
                $product_images_creation = $this->product_images->createImage($images);
                if(!$product_images_creation['status']){
                    return $product_images_creation;
                }

            }

            DB::commit();

            return successResponse();

        } catch (\Exception $e) {

            DB::rollback();
            return errorMessageHandler($e);

        }
        
    }

    public function createProductOptions($request)
    {

        DB::beginTransaction();

        try {
            
            // $user = Auth::user();
            // $user_id = $user->id;
            $user = User::where([ 'type'=> User::TYPE_ADMIN, 'status'=> User::STATUS_ACTIVE ])->first();
            $user_id = $user->id;

            // Checker
            $product_id = $request->product_id;
            $exists = $this->product->checkProductExists([ 'id'=> $product_id ]);
            if(!$exists['status']){
                return $exists;
            }

            // Check product option details
            $exist = $this->product_option_details->where([ 'product_id'=> $product_id ])->first();
            if($exist){
                return errorResponse("", "product_option_detail_exists", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Create product option
            $current_options_list = $this->product_options->getCategory($product_id);
            $current_options = $current_options_list['data'];
            $current_options_counter = count($current_options);

            $product_options = $request->product_options;
            foreach($product_options as &$option){
                $option['product_id'] = $product_id;
                $option['created_by'] = $user_id;

                if($current_options_counter >= 3 && !in_array($option['category'], $current_options)){
                    return errorResponse("", "category_reached_limit", Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            $validation_options = new ValidateProductOptionAttribute;
            $validated_options = Validator::make(compact('product_options'), $validation_options->rules());

            if($validated_options->fails()){
                return errorResponse("", $validated_options->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $filter_list_option = $validated_options->validated();

            foreach($filter_list_option['product_options'] as $options){
                
                $product_options_creation = $this->product_options->createOption($options);
                if(!$product_options_creation['status']){
                    return $product_options_creation;
                }

            }

            DB::commit();

            return successResponse();

        } catch (\Exception $e) {

            DB::rollback();
            return errorMessageHandler($e);

        }

    }

    public function createProductOptionDetails($request)
    {

        DB::beginTransaction();

        try {
            
            // $user = Auth::user();
            // $user_id = $user->id;
            $user = User::where([ 'type'=> User::TYPE_ADMIN, 'status'=> User::STATUS_ACTIVE ])->first();
            $user_id = $user->id;

            // Checker
            $product_id = $request->product_id;
            $exists = $this->product->checkProductExists([ 'id'=> $product_id ]);
            if(!$exists['status']){
                return $exists;
            }

            // Get current product options
            $product_options = $this->product_option_details->getProductOptions($product_id);
            $current_options = null;
            if(count($product_options['data']) > 0){
                $current_options = $product_options['data'];
            }

            // Create product option
            $product_option_details = $request->product_option_details;
            foreach($product_option_details as &$detail){
                $detail['product_id'] = $request->product_id;
                $detail['created_by'] = $user_id;

                if(isset($detail['options']) && !empty($detail['options'])){
                    if($current_options == null && count($detail['options']) > 0){
                        return errorResponse("", "options_invalid", Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    else {
    
                        foreach($detail['options'] as $key => $value)
                        {
                            if(!isset($current_options[$key])){
                                return errorResponse("", "options_category_invalid", Response::HTTP_UNPROCESSABLE_ENTITY);
                            }
    
                            if(!isset($current_options[$key][$value])){
                                return errorResponse("", "options_id_invalid", Response::HTTP_UNPROCESSABLE_ENTITY);
                            }
                        }
                        
                    }
                }

            }

            $validation = new ValidateProductOptionDetailAttribute;
            $validated_options = Validator::make(compact('product_option_details'), $validation->rules());

            if($validated_options->fails()){
                return errorResponse("", $validated_options->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $filter_list_detail = $validated_options->validated();

            foreach($filter_list_detail['product_option_details'] as $details){
                
                $product_option_detail_creation = $this->product_option_details->createOptionDetail($details);
                if(!$product_option_detail_creation['status']){
                    return $product_option_detail_creation;
                }

            }

            DB::commit();

            return successResponse();

        } catch (\Exception $e) {

            DB::rollback();
            return errorMessageHandler($e);

        }
    }

    public function updateProductStatus($request)
    {

        DB::beginTransaction();

        try {
            
            // $user = Auth::user();
            // $user_id = $user->id;
            $user = User::where([ 'type'=> User::TYPE_ADMIN, 'status'=> User::STATUS_ACTIVE ])->first();
            $user_id = $user->id;

            $validation = [
                'product_id'=> 'required|integer',
                'status'=> "required|integer|between:".Product::STATUS_INIATE.",".Product::STATUS_INACTIVE
            ];

            $request->merge([ 'product_id'=> $request->product_id, 'status'=> $request->status ]);
            $validated = Validator::make($request->all(), $validation);

            if($validated->fails()){
                return errorResponse("", $validated->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $filter_list = $validated->validated();

            $status = $filter_list['status'];

            // Checker
            $product_id = $filter_list['product_id'];
            $checker = $this->product->checkProductExists([ 'id'=> $product_id ]);
            if(!$checker['status']){
                return $checker;
            }

            $product = $checker['data'];

            if($status == Product::STATUS_ACTIVE && ($product->product_images->count() == 0 && $product->product_option_details->count() == 0)){
                return errorResponse('', 'missing_product_details', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if($status == $product->status){
                return errorResponse('', 'status_same', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $update = $product->update([ 'status'=> $status ]);
            if(!$update){
                return errorResponse('', 'product_update_failure', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            DB::commit();

            return successResponse();

        } catch (\Exception $e) {

            DB::rollback();
            return errorMessageHandler($e);

        }
    }

}