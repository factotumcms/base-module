<?php

namespace Wave8\Factotum\Base\Enums\Media;

use Wave8\Factotum\Base\Traits\ListCases;

enum MediaType: string
{
    use ListCases;
    case IMAGE = 'image';
    case VIDEO = 'video';
    case AUDIO = 'audio';
    case PDF = 'pdf';
    case FILE = 'file';
}
