<?php

namespace Wave8\Factotum\Base\Enum;

use Wave8\Factotum\Base\Traits\ListCases;

enum Permission: string
{
    use ListCases;
    case EDIT_USER = 'edit_user';
    case VIEW_USER = 'view_user';
    case EDIT_ROLES = 'edit_roles';
    case VIEW_ROLES = 'view_roles';

}
