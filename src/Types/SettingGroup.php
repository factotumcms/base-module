<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ListCases;

enum SettingGroup: string
{
    use ListCases;

    case MEDIA = 'media';
    case AUTH = 'auth';
}
