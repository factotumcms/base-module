<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ListCases;

enum SettingType: string
{
    use ListCases;

    case AUTH_IDENTIFIER = 'auth_identifier';
    case THUMB_SIZE_WIDTH = 'thumb_size_width';
    case THUMB_SIZE_HEIGHT = 'thumb_size_height';
    case THUMB_QUALITY = 'thumb_quality';
    case RESIZE_QUALITY = 'resize_quality';
}
