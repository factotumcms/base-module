<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ConstantsList;

abstract class SettingDataType
{
    use ConstantsList;

    const string INTEGER = 'integer';

    const string FLOAT = 'float';

    const string STRING = 'string';

    const string BOOLEAN = 'boolean';

    const string JSON = 'json';
}
