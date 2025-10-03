<?php

namespace Wave8\Factotum\Base\Enums\Setting;

use Wave8\Factotum\Base\Traits\ListCases;

enum SettingScope: string
{
    use ListCases;

    case SYSTEM = 'system';
    case USER = 'user';
}
