<?php

namespace Wave8\Factotum\Base\Exceptions;

use Exception;
use Throwable;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;

class AuthenticationException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        if (empty($message)) {
            $message = __('auth.failed');
        }
        parent::__construct($message, $code, $previous);
    }

    public function render(): ApiResponse
    {
        return ApiResponse::unauthorized(['message' => $this->getMessage()]);
    }
}
