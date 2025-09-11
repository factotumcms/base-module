<?php

namespace Wave8\Factotum\Base\Traits;

use Illuminate\Support\Collection;

trait ListCases
{
    public static function getValues(): Collection
    {
        return collect(self::cases())->map(fn ($case) => $case->value);
    }
}
