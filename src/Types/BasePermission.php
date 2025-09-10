<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ConstantsList;

abstract class BasePermission
{
    use ConstantsList;

    const string EDIT_USER = 'edit_user';

    const string VIEW_USER = 'view_user';

    const string EDIT_ROLES = 'edit_roles';

    const string VIEW_ROLES = 'view_roles';
}
