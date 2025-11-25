<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Wave8\Factotum\Base\Models\User;

describe('User model', function () {
    it('checks default admin user exists', function () {
        $user = User::where('email', config('factotum_base.admin_default.email'))->firstOrFail();

        expect($user)->toBeInstanceOf(User::class)
            ->and($user->first_name)->toBe(config('factotum_base.admin_default.first_name'))
            ->and($user->last_name)->toBe(config('factotum_base.admin_default.last_name'))
            ->and($user->username)->toBe(config('factotum_base.admin_default.username'));
    });

    it('checks User model relations', function () {
        $user = User::find(1);

        expect($user->roles())->toBeInstanceOf(MorphToMany::class)
            ->and($user->permissions())->toBeInstanceOf(MorphToMany::class)
            ->and($user->roles)->toBeInstanceOf(Collection::class)
            ->and($user->permissions)->toBeInstanceOf(Collection::class);
    });

    it('checks User model fillable properties', function () {
        $model = new User;

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
