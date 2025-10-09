<?php

use Wave8\Factotum\Base\Models\User;

describe('UserModel', function () {
    it('successfully creates a new User model instance', function () {
        $user = new User;

        expect($user)->toBeInstanceOf(User::class);
    });

    it('checks fillable columns', function () {

        $user = new User;

        expect($user->getFillable())->toEqual([
            'first_name',
            'last_name',
            'username',
            'email',
            'email_verified_at',
            'password',
            'is_active',
            'remember_token',
        ]);
    });
});
