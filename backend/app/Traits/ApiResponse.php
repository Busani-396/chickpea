<?php

namespace App\Traits;


trait ApiResponse{
    protected function success($data, string $message = '', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'status_code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error(string $message = '', int $code, $details = null)
    {
        return response()->json([
            'success' => false,
            'status_code' => $code,
            'message' => $message,
            'errors' => $details
        ], $code);
    }
}