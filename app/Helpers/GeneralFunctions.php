<?php

    function finalResponse($request){

        $status = $request['status'];
        $data = $request['data'];
        $message = $request['message'];
        $code = 200;

        if(!$status){
            $code = $request['code'];

            if(!is_integer($code)){
                $code=500;
            }

        }

        return response()->json(compact('status', 'data', 'message', 'code'), $code);
    }

    function errorResponse($data="", $message, $code){

        $new_array = compact('data', 'message', 'code');
        $new_array['status'] = false;

        return $new_array;
    }

    function successResponse($data="", $message="success"){
        $new_array = compact('data', 'message');
        $new_array['status'] = true;
        $new_array['code'] = 200;

        return $new_array;
    }
