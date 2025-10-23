<?php

namespace Wave8\Factotum\Base\Enums\Permission;

use Wave8\Factotum\Base\Traits\ListCases;

enum MediaPermission: string
{
    use ListCases;
    case UPLOAD_MEDIA = 'upload_media';
    case READ_MEDIA = 'read_media';
}
