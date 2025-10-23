<?php

namespace Wave8\Factotum\Base\Enums\Permission;

use Wave8\Factotum\Base\Traits\ListCases;

enum RolePermission: string
{
    use ListCases;
    case CREATE_ROLE = 'create_role';
    case READ_ROLE = 'read_role';
    case UPDATE_ROLE = 'update_role';
    case DELETE_ROLE = 'delete_role';
}
