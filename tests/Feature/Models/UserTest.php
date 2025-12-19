<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Wave8\Factotum\Base\Contracts\Api\UserServiceInterface;
use Wave8\Factotum\Base\Models\User;

describe('User model', function () {
    it('checks default admin user exists', function () {
        $userService = app(UserServiceInterface::class);
        $user = $userService->getBy('email', config('factotum_base.admin_default.email'))->firstOrFail();

        expect($user)->toBeInstanceOf(User::class)
            ->and($user->first_name)->toBe(config('factotum_base.admin_default.first_name'))
            ->and($user->last_name)->toBe(config('factotum_base.admin_default.last_name'))
            ->and($user->username)->toBe(config('factotum_base.admin_default.username'));
    });

    it('checks User model relations', function () {
        $userService = app(UserServiceInterface::class);
        $user = $userService->read(1);

        expect($user->roles())->toBeInstanceOf(MorphToMany::class)
            ->and($user->permissions())->toBeInstanceOf(MorphToMany::class)
            ->and($user->notifications())->toBeInstanceOf(MorphMany::class)
            ->and($user->settings())->toBeInstanceOf(BelongsToMany::class)
            ->and($user->roles)->toBeInstanceOf(Collection::class)
            ->and($user->permissions)->toBeInstanceOf(Collection::class)
            ->and($user->notifications)->toBeInstanceOf(Collection::class)
            ->and($user->settings)->toBeInstanceOf(Collection::class);
    });

    it('checks User model fillable properties', function () {
        $userService = app(UserServiceInterface::class);
        $model = $userService->read(1);

        $fillable = [
            'first_name',
            'last_name',
            'username',
            'email',
            'password',
            'is_active',
        ];

        expect($model->getFillable())->toEqual($fillable);
    });
});
