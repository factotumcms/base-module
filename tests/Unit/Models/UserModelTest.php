<?php

describe('UserModel', function () {
    it('successfully creates a new User model instance', function () {
        $user = new \Wave8\Factotum\Base\Models\User();

        expect($user)->toBeInstanceOf(\Wave8\Factotum\Base\Models\User::class);
    });

    it('checks fillable columns', function () {

        $user = new \Wave8\Factotum\Base\Models\User();

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



