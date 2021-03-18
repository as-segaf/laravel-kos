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
    public function success($code, $message, $data)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ],$code);
    }

    /**
     * Send success response
     * 
     * @param int $code
     * @param string $message
     */
    public function error($code, $message)
    {
        return response()->json([
            'code' => $code,
            'message' => $message
        ],$code);
    }
}
