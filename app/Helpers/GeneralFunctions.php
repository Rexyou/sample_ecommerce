<?php

    function finalResponse($request){

        $status = $request['status'];
        $data = $request['data'];
        $message = $request['message'];
        $code = 200;

        if(!$status){
            $code = $request['code'];

            if(!is_integer($code) || empty($code)){
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

    function errorMessageHandler($e)
    {
        $message = $e->getMessage()." at line ".$e->getLine()." in ".$e->getFile();
        return errorResponse('', $message, $e->getCode());
    }

    function encrptList($list)
    {
        $encrypter = new Illuminate\Encryption\Encrypter(config('app.cart_secret'), 'AES-128-CBC');
        $encrypted = $encrypter->encrypt($list);
        return $encrypted;
    }

    function decryptList($key)
    {
        $decrypter = new Illuminate\Encryption\Encrypter(config('app.cart_secret'), 'AES-128-CBC');
        $decrypted = $decrypter->decrypt($key);
        return $decrypted;
    }
