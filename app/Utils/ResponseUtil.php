<?php

namespace App\Utils;

class ResponseUtil
{
    public static function responseStandard($status, $data = null, $error = null, $code = 200)
    {
        $response = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
        ];
        return response()->json($response, $code);
    }
}