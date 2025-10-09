<?php

use Wave8\Factotum\Base\Tests\TestCase;

use function PHPUnit\Framework\assertTrue;

it('feature tests work', function () {

    assertTrue(Schema::hasColumn('users', 'email'));

})->uses(TestCase::class);
