<?php

namespace App\Traits\Response;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait HasApiResponse
{
    public function send($data = null, $message = '', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode < 400,
        ], $statusCode);
    }

    public function noContent(): JsonResponse
    {
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function ok(): JsonResponse
    {
        return response()->json(new \stdClass(), status: Response::HTTP_OK);
    }
}
