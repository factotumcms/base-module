<?php

namespace Wave8\Factotum\Base\Enums\Permission;

use Wave8\Factotum\Base\Traits\ListCases;

enum NotificationPermission: string
{
    use ListCases;
    case VIEW_NOTIFICATIONS = 'view_notifications';
}
