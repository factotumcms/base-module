<?php

namespace Wave8\Factotum\Base\Enums\Setting;

use Wave8\Factotum\Base\Traits\ListCases;

enum Setting: string
{
    use ListCases;

    case AUTH_TYPE = 'auth_type';
    case AUTH_BASIC_IDENTIFIER = 'auth_basic_identifier';
    case USER_AVATAR_PRESET = 'user_avatar_preset';
    case THUMBNAIL_PRESET = 'thumbnail_preset';
    case RESIZE_QUALITY = 'resize_quality';
    case DEFAULT_MEDIA_DISK = 'default_media_disk';
    case MEDIA_BASE_PATH = 'media_base_path';
    case MEDIA_CONVERSIONS_PATH = 'media_conversions_path';
    case LOCALE = 'locale';
    case LOCALE_AVAILABLE = 'locale_available';
}
