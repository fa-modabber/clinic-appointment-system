<?php

namespace App\Traits;

trait ApiResponser
{
    public function responseSuccess($code = 200, $message = null, $data = [],)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function responseError($message = 'Something went wrong', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
        ], $code);
    }
}
