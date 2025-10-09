<?php

use Wave8\Factotum\Base\Dtos\Api\Backoffice\Auth\LoginUserDto;
use Wave8\Factotum\Base\Dtos\Api\Backoffice\Auth\RegisterUserDto;
use Wave8\Factotum\Base\Models\User;
use function PHPUnit\Framework\assertTrue;

it('feature tests work', function () {

    assertTrue(Schema::hasColumn('users', 'email'));

})->uses(\Wave8\Factotum\Base\Tests\TestCase::class);

