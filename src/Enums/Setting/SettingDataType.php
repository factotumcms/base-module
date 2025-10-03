<?php

namespace Wave8\Factotum\Base\Enums\Setting;

use Wave8\Factotum\Base\Traits\ListCases;

enum SettingDataType: string
{
    use ListCases;

    case INTEGER = 'integer';
    case FLOAT = 'float';
    case STRING = 'string';
    case BOOLEAN = 'boolean';
    case JSON = 'json';
}
