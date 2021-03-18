<?php

namespace App\Traits;

trait ResponseStructure
{
    /**
     * Send success response
     * 
     * @param int $code
     * @param string $message
     * @param array $data
     */
    public function successResponse($code, $message, $data, $token = null)
    {
        if ($token == null) {
            return response()->json([
                'code' => $code,
                'message' => $message,
                'data' => $data
            ],$code);
        }
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'token' => $token
        ]);
    }

    /**
     * Send success response
     * 
     * @param int $code
     * @param string $message
     */
    public function errorResponse($code, $message)
    {
        return response()->json([
            'code' => $code,
            'message' => $message
        ],$code);
    }
}
