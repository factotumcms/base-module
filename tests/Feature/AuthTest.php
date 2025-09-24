<?php

it('can login to the application', function () {

    $response = $this->postJson('/api/v1/base/login', [
        'email' => config('factotum-base.admin_default.email'),
        'password' => config('factotum-base.admin_default.password'),
    ]);

    $response->assertStatus(200);
});
