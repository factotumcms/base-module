<?php

namespace Wave8\Factotum\Base\Http\Responses\Api\Auth;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Resources\UserResource;

final class LoginResponse
{
    public static function createSuccessful(User $user): JsonResponse
    {
        return response()->json([
            'message' => __('auth.login_successful'),
            'data' => ['user' => UserResource::from($user)],
            'access_token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public static function createFailed(): JsonResponse
    {
        return response()->json([
            'message' => __('auth.login_failed'),
        ], Response::HTTP_UNAUTHORIZED);
    }
}
