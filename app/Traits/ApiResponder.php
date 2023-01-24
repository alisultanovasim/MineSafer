<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

//use Laravel\Lumen\Http\ResponseFactory;

/**
 * Trait ApiResponder
 * @package App\Traits
 */
trait ApiResponder
{

    /**
     * Data Response
     * @param $data
     * @param int $code
     * @return JsonResponse
     */
    public function dataResponse($data, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['content' => $data], $code);
    }

    /**
     * Success Response
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse($message, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['success' => $message, 'code' => $code], $code);
    }

//    public function successResponse($message, $data = [], $status = 200): JsonResponse
//    {
//        return response()->json([
//            'success' => true,
//            'data' => $data,
//            'message' => $message,
//        ], $status);
//    }

    /**
     * Error Response
     * @param $message
     * @param int $code
     * @return JsonResponse
     *
     */
    public function errorResponse($message, $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

}
