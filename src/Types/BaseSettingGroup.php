<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ConstantsList;

abstract class BaseSettingGroup
{
    use ConstantsList;

    const string MEDIA = 'media';

    const string AUTH = 'auth';
}
