<?php

namespace Wave8\Factotum\Base\Helpers;

use Illuminate\Support\Str;

class Utility
{
    public static function sanitizeQueryString(array $queryStrings = []): array
    {
        $result = [];
        foreach ($queryStrings as $key => $value) {
            $result[self::toCamelCase($key)] = $value;
        }

        return $result;
    }

    public static function toCamelCase(string $string): string
    {
        return Str::camel($string);
    }
}
