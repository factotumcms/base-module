<?php

namespace Wave8\Factotum\Base\Helpers;

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
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }
}
