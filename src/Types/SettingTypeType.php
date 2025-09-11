<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ListCases;

enum SettingTypeType: string
{
    use ListCases;

    case SYSTEM = 'system';
    case USER = 'user';
}
