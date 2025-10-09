<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use Wave8\Factotum\Base\Models\User;
use Wave8\Factotum\Base\Tests\TestCase;


describe('User model', function() {
    it('checks default admin user exists', function () {

        $user = User::where('email', config("factotum-base.admin_default.email"))->firstOrFail();

        expect($user)->toBeInstanceOf(User::class)
            ->and($user->first_name)->toBe(config("factotum-base.admin_default.first_name"))
            ->and($user->last_name)->toBe(config("factotum-base.admin_default.last_name"))
            ->and($user->username)->toBe(config("factotum-base.admin_default.username"));

    })->uses(TestCase::class);

    it('checks User model relations', function () {

        $user = User::find(1);

        expect($user->roles())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphToMany::class)
            ->and($user->permissions())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphToMany::class)
            ->and($user->roles)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class)
            ->and($user->permissions)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);

    })->uses(TestCase::class);

    it('checks User model fillable properties', function () {

        $model = new User();

        $fillable = [
            'first_name',
            'last_name',
            'username',
            'email',
            'email_verified_at',
            'password',
            'is_active',
            'remember_token',
        ];

        expect($model->getFillable())->toEqual($fillable);

    })->uses(TestCase::class);

});
