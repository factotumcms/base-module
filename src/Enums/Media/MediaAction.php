<?php

namespace Wave8\Factotum\Base\Enums\Media;

use Wave8\Factotum\Base\Traits\ListCases;

enum MediaAction: string
{
    use ListCases;
    case FIT = 'fit';
    case CROP = 'crop';
    case RESIZE = 'resize';
    case OPTIMIZE = 'optimize';
}
