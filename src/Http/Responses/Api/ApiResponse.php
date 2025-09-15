<?php

namespace Wave8\Factotum\Base\Http\Responses\Api;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponse extends JsonResponse
{
    public static function make(mixed $data, int $status = Response::HTTP_OK): static
    {
        return new self(
            data: $data,
            status: $status
        );
    }

    public static function createSuccessful(string $message, mixed $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ]);
    }
}
