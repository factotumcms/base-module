<?php

namespace Wave8\Factotum\Base\Enum;

use Wave8\Factotum\Base\Traits\ListCases;

enum MediaPreset: string
{
    use ListCases;
    case PROFILE_PICTURE = 'profile_picture_preset';
    case THUMBNAIL = 'thumbnail_preset';
}
