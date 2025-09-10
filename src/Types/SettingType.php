<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ConstantsList;

abstract class SettingType
{
    use ConstantsList;

    const string USER = 'user';

    const string SYSTEM = 'system';
}
