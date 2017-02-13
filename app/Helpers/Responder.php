<?php namespace App\Helpers;

class Responder
{
    public static function success($data = null, string $message = '')
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ]);
    }

    public static function error($data = null, string $error = '', $responseCode = 200)
    {
        return response()->json([
            'success' => false,
            'error' => $error,
            'data' => $data,
        ], $responseCode);
    }

    public static function noJsonSuccess($data = null, string $message = '')
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
    }

    public static function noJsonError($data = null, string $error = '', string $error_code = '')
    {
        return [
            'success' => false,
            'error' => $error,
            'error_code' => $error_code,
            'data' => $data,
        ];
    }
}
