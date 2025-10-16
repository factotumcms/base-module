<?php

namespace Wave8\Factotum\Base\Enums\Media;

use Wave8\Factotum\Base\Traits\ListCases;

enum MediaPreset: string
{
    use ListCases;
    case USER_AVATAR = 'user_avatar_preset';
    case THUMBNAIL = 'thumbnail_preset';
}
