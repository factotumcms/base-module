<?php

namespace Wave8\Factotum\Base\Http\Responses\Api;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponse
{
    public static function createSuccessful(string $message, mixed $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function createFailed(string $message, mixed $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function createCustom(?string $message, int $status, mixed $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
