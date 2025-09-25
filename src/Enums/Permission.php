<?php

namespace Wave8\Factotum\Base\Enums;

use Wave8\Factotum\Base\Traits\ListCases;

enum Permission: string
{
    use ListCases;
    case CREATE_USER = 'create_user';
    case READ_USERS = 'read_user';
    case UPDATE_USERS = 'edit_users';
    case UPDATE_OWN_USER = 'edit_own_user';
    case DELETE_USERS = 'delete_users';
    case UPLOAD_MEDIA = 'upload_media';
    case READ_MEDIA = 'read_media';
    case READ_SETTINGS = 'read_settings';
    case UPDATE_SETTINGS = 'update_settings';
    case CREATE_ROLE = 'create_role';
    case READ_ROLE = 'read_role';
    case UPDATE_ROLE = 'update_role';
    case DELETE_ROLE = 'delete_role';
    case READ_PERMISSIONS = 'read_permissions';
}
