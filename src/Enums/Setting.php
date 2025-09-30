<?php

namespace Wave8\Factotum\Base\Enums;

use Wave8\Factotum\Base\Traits\ListCases;

enum Setting: string
{
    use ListCases;

    case AUTH_TYPE = 'auth_type';
    case AUTH_BASIC_IDENTIFIER = 'auth_basic_identifier';
    case PROFILE_PICTURE_PRESET = 'profile_picture_preset';
    case THUMBNAIL_PRESET = 'thumbnail_preset';
    case RESIZE_QUALITY = 'resize_quality';
    case DEFAULT_MEDIA_DISK = 'default_media_disk';
    case MEDIA_BASE_PATH = 'media_base_path';
    case LOCALE_DEFAULT = 'locale_default';
    case LOCALE_AVAILABLE = 'locale_available';
    case PAGINATION_TYPE = 'pagination_type';
    case PAGINATION_PER_PAGE = 'pagination_per_page';
    case PAGINATION_DEFAULT_ORDER_BY = 'pagination_default_order_by';
    case PAGINATION_DEFAULT_ORDER_DIRECTION = 'pagination_default_order_direction';
}
