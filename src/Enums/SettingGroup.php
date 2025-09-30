<?php

namespace Wave8\Factotum\Base\Enums;

use Wave8\Factotum\Base\Traits\ListCases;

enum SettingGroup: string
{
    use ListCases;

    case MEDIA = 'media';
    case AUTH = 'auth';
    case LOCALE = 'locale';
    case PAGINATION = 'pagination';
}
