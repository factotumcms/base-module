<?php

namespace Wave8\Factotum\Base\Traits;

use Illuminate\Support\Collection;

trait ConstantsList
{
    public static function getValues(): Collection
    {
        $reflection = new \ReflectionClass(self::class);

        return collect($reflection->getConstants());
    }
}
