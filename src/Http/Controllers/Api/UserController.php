<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Illuminate\Support\Facades\Gate;
use Wave8\Factotum\Base\Contracts\Services\UserServiceInterface;
use Wave8\Factotum\Base\Dto\User\CreateUserDto;
use Wave8\Factotum\Base\Dto\User\UpdateUserDto;
use Wave8\Factotum\Base\Http\Requests\Api\User\CreateUserRequest;
use Wave8\Factotum\Base\Http\Requests\Api\User\UpdateUserRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Resources\UserResource;

final readonly class UserController
{
    public function __construct(
        private UserServiceInterface $userService,
    ) {}

    public function index(): ApiResponse
    {
        Gate::authorize('read', User::class);

        $users = $this->userService->getAll();

        return ApiResponse::make(
            data: $users->map(fn ($el) => UserResource::from($el)),
        );

    }

    public function store(CreateUserRequest $request): ApiResponse
    {
        Gate::authorize('create', User::class);

        $user = $this->userService->create(
            data: CreateUserDto::from($request->all())
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function show(int $id): ApiResponse
    {
        Gate::authorize('read', User::class);

        $user = $this->userService->show(
            id: $id
        );

        return ApiResponse::make(
            data: UserResource::from($user)
        );
    }

    public function update(int $id, UpdateUserRequest $request): ApiResponse
    {
        Gate::authorize('update', User::class);

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
        Gate::authorize('delete', User::class);

        $this->userService->delete($id);

        return ApiResponse::make(
            data: 'ok'
        );

    }
}
