<?php

namespace Wave8\Factotum\Base\Enums\Permission;

use Wave8\Factotum\Base\Traits\ListCases;

enum SettingPermission: string
{
    use ListCases;
    case READ_SETTINGS = 'read_settings';
    case UPDATE_SETTINGS = 'update_settings';

}
