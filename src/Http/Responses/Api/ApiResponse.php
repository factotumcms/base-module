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
    public static function __callStatic($name, $arguments): static
    {
        if (Str::startsWith($name, 'Http')) {
            $statusConstant = 'self::HTTP_'.Str::upper(Str::snake(Str::after($name, 'Http')));
            if (defined($statusConstant)) {
                $status = constant($statusConstant);
                $headers = $arguments[0] ?? [];

                return new static(
                    data: null,
                    status: $status,
                    headers: $headers
                );
            }
        }

        throw new \BadMethodCallException("Method {$name} not supported.");
    }
}
