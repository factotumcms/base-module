<?php

namespace Wave8\Factotum\Base\Http\Controllers\Api;

use Wave8\Factotum\Base\Contracts\Api\SettingServiceInterface;
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Dtos\Api\Setting\UpdateSettingDto;
use Wave8\Factotum\Base\Dtos\Api\User\CreateUserDto;
use Wave8\Factotum\Base\Dtos\Api\User\UpdateUserDto;
use Wave8\Factotum\Base\Enums\Setting\Setting;
use Wave8\Factotum\Base\Enums\Setting\SettingGroup;
use Wave8\Factotum\Base\Http\Requests\Api\Setting\UpdateSettingRequest;
use Wave8\Factotum\Base\Http\Requests\Api\User\CreateUserRequest;
use Wave8\Factotum\Base\Http\Requests\Api\User\UpdateUserPasswordRequest;
use Wave8\Factotum\Base\Http\Requests\Api\User\UpdateUserRequest;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Resources\Api\SettingResource;
use Wave8\Factotum\Base\Resources\Api\UserResource;
use Wave8\Factotum\Base\Services\Api\SettingService;
use Wave8\Factotum\Base\Services\Api\UserService;

final readonly class UserController
{
    private string $userResource;

    private string $settingResource;

    final public function __construct(
        /** @var $userservice UserService */
        private UserServiceInterface $userService,
        /** @var $settingService SettingService */
        private SettingServiceInterface $settingService,
    ) {
        $this->userResource = config('data_transfer.'.UserResource::class);
        $this->settingResource = config('data_transfer.'.SettingResource::class);
    }

    final public function index(): ApiResponse
    {
        $users = $this->userService->filter();

        return ApiResponse::make(
            data: $this->userResource::collect($users),
        );
    }

    final public function store(CreateUserRequest $request): ApiResponse
    {
        $createUserDto = config('data_transfer.'.CreateUserDto::class);

        $user = $this->userService->create(
            data: $createUserDto::from($request)
        );

        return ApiResponse::make(
            data: $this->userResource::from($user),
            status: ApiResponse::HTTP_CREATED
        );
    }

    final public function show(User $user): ApiResponse
    {
        return ApiResponse::make(
            data: $this->userResource::from($user->load('avatar')),
        );
    }

    final public function update(User $user, UpdateUserRequest $request): ApiResponse
    {
        $updateUserDto = config('data_transfer.'.UpdateUserDto::class);

        $user = $this->userService->update(
            user: $user,
            data: $updateUserDto::from($request)
        );

        return ApiResponse::make(
            data: $this->userResource::from($user),
        );
    }

    final public function changePassword(UpdateUserPasswordRequest $request): ApiResponse
    {
        $user = $this->userService->updatePassword($request->password);

        $tokenExpirationDays = $this->settingService->getValue(
            key: Setting::AUTH_TOKEN_EXPIRATION_DAYS,
            group: SettingGroup::AUTH,
        );

        return ApiResponse::ok($this->userResource::from($user)->additional([
            'access_token' => $user->createToken(name: 'auth_token', expiresAt: now()
                ->addDays($tokenExpirationDays))
                ->plainTextToken,
        ]));
    }

    final public function destroy(User $user): ApiResponse
    {
        $this->userService->delete(
            id: $user->id,
        );

        return ApiResponse::noContent();
    }

    final public function updateSetting(int $id, Setting $setting, UpdateSettingRequest $request): ApiResponse
    {
        $updateSettingDto = config('data_transfer.'.UpdateSettingDto::class);

        $user = $this->userService->updateSetting(
            id: $id,
            settingId: $setting->id,
            data: $updateSettingDto::from($request)
        );

        return ApiResponse::make(
            data: $this->userResource::from($user),
        );
    }

    public function settings(): ApiResponse
    {
        /** @var $settingService SettingService */
        $settingService = app(SettingServiceInterface::class);

        return ApiResponse::make(
            data: $this->settingResource::collect(
                $settingService->getAll()
            ),
        );
    }
}
