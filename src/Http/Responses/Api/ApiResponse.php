<?php

namespace Wave8\Factotum\Base\Http\Responses\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

final class ApiResponse extends JsonResponse
{
    public static function make(mixed $data, ?array $headers = null, int $status = self::HTTP_OK): static
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

    /**
     * Handle dynamic static calls into the method based on the Http statuses.
     */
    public static function __callStatic($method, $parameters): static
    {
        $constant = 'self::HTTP_'.Str::upper(Str::snake($method));

        if (! defined($constant)) {
            return parent::$method(...$parameters);
        }

        $status = constant($constant);
        $data = $parameters[0] ?? [];
        $headers = $parameters[1] ?? [];

        return static::make(
            data: $data,
            headers: $headers,
            status: $status,
        );
    }
}
