<?php

namespace Wave8\Factotum\Base\Http\Responses\Api;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Resources\UserResource;

final class ApiResponse
{
    public static function createSuccessful(string $message, mixed $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function createFailed(string $message, mixed $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
