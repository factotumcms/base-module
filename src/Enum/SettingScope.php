<?php

namespace Wave8\Factotum\Base\Enum;

use Wave8\Factotum\Base\Traits\ListCases;

enum SettingScope: string
{
    use ListCases;

    case SYSTEM = 'system';
    case USER = 'user';
}
