<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ConstantsList;

abstract class BaseSetting
{
    use ConstantsList;

    const string THUMB_SIZE_WIDTH = 'thumb_size_width';

    const string THUMB_SIZE_HEIGHT = 'thumb_size_height';

    const string THUMB_QUALITY = 'thumb_quality';

    const string RESIZE_QUALITY = 'resize_quality';
}
