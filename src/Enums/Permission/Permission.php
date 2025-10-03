<?php

namespace Wave8\Factotum\Base\Enums\Permission;

use Wave8\Factotum\Base\Traits\ListCases;

enum Permission: string
{
    use ListCases;

    case READ_PERMISSIONS = 'read_permissions';
}
