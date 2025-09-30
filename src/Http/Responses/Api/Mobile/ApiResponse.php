<?php

namespace Wave8\Factotum\Base\Http\Responses\Api\Mobile;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponse extends JsonResponse
{
    public static function make(mixed $data, ?array $headers = null, int $status = Response::HTTP_OK): static
    {
        $response = new self(
            data: $data,
            status: $status
        );

        if ($headers) {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
        }

        return $response;
    }
}
