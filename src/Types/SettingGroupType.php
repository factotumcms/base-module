<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ListCases;

enum SettingGroupType: string
{
    use ListCases;

    case MEDIA = 'media';
    case AUTH = 'auth';
}
