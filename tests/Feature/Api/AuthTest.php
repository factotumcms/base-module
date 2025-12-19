<?php

describe('Auth apis', function () {

    it('checks login', function () {
        $this->postJson('api/v1/auth/login', [
            'email' => config('factotum_base.admin_default.email'),
            'password' => config('factotum_base.admin_default.password'),
        ])->assertStatus(200);
    });
});
