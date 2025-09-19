<?php

namespace Wave8\Factotum\Base\Enum;

use Wave8\Factotum\Base\Traits\ListCases;

enum Setting: string
{
    use ListCases;

    case AUTH_TYPE = 'auth_type';
    case AUTH_BASIC_IDENTIFIER = 'auth_basic_identifier';
    case THUMB_SIZE_WIDTH = 'thumb_size_width';
    case THUMB_SIZE_HEIGHT = 'thumb_size_height';
    case THUMB_QUALITY = 'thumb_quality';
    case RESIZE_QUALITY = 'resize_quality';
    case LOCALE_DEFAULT = 'locale_default';
    case LOCALE_AVAILABLE = 'locale_available';
}
