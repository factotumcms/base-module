<?php

namespace Wave8\Factotum\Base\Enum;

use Wave8\Factotum\Base\Traits\ListCases;

enum Permission: string
{
    use ListCases;
    case CREATE_USER = 'create_user';
    case READ_USER = 'read_user';
    case UPDATE_USER = 'edit_user';
    case DELETE_USER = 'delete_user';
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
