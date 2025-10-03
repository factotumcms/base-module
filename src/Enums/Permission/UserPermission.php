<?php

namespace Wave8\Factotum\Base\Enums\Permission;

use Wave8\Factotum\Base\Traits\ListCases;

enum UserPermission: string
{
    use ListCases;
    case CREATE_USERS = 'create_user';
    case READ_USERS = 'read_user';
    case UPDATE_USERS = 'edit_users';
    case DELETE_USERS = 'delete_users';

}
