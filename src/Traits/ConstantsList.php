<?php

namespace Wave8\Factotum\Base\Traits;

trait ConstantsList
{
    public static function getValues(): array
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();
    }
}
