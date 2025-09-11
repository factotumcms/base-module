<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Wave8\Factotum\Base\Contracts\Controllers\CrudController;
use Wave8\Factotum\Base\Contracts\Services\UserService;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;

class UserController extends Controller implements CrudController
{
    private readonly UserService $userService;

    public function __construct(
        UserService $userService,
    ) {
        $this->userService = $userService;
    }

    public function all(): JsonResponse
    {
        $data = $this->userService->getAll();

        if($data->isEmpty()){
            return ApiResponse::createFailed('No users found');
        }

        return ApiResponse::createSuccessful('Users retrieved successfully', $data);
    }

    public function read(int $id): JsonResponse
    {

    }
}
