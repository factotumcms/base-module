<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api\Backoffice;

use Wave8\Factotum\Base\Contracts\Api\Backoffice\UserServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\User\UpdateUserDto;
use Wave8\Factotum\Base\Dtos\QueryFiltersDto;
use Wave8\Factotum\Base\Helpers\Utility;
use Wave8\Factotum\Base\Http\Requests\Api\Backoffice\User\CreateUserRequest;
use Wave8\Factotum\Base\Http\Requests\Api\Backoffice\User\UpdateUserRequest;
use Wave8\Factotum\Base\Http\Requests\Api\QueryFiltersRequest;
use Wave8\Factotum\Base\Http\Responses\Api\Backoffice\ApiResponse;
use Wave8\Factotum\Base\Resources\Api\Backoffice\UserResource;

final readonly class UserController
{
    public function __construct(
        private UserServiceInterface $userService,
    ) {}

    public function index(QueryFiltersRequest $request): ApiResponse
    {
        $users = $this->userService
            ->filter(
                QueryFiltersDto::make(
                    ...Utility::sanitizeQueryString($request->query())
                )
            );

        return ApiResponse::make(
            data: UserResource::collect($users)
        );
    }

    public function store(CreateUserRequest $request): ApiResponse
    {
        $user = $this->userService->create(
            data: CreateUserDto::from($request->all())
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function show(int $id): ApiResponse
    {
        $user = $this->userService->show(
            id: $id
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function update(int $id, UpdateUserRequest $request): ApiResponse
    {
        $user = $this->userService->update(
            id: $id,
            data: UpdateUserDto::from($request->all())
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function destroy(int $id): ApiResponse
    {
        $this->userService->delete($id);

        return ApiResponse::make(
            data: 'ok'
        );
    }
}
