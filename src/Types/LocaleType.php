<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ListCases;

enum LocaleType: string
{
    use ListCases;

    case it_IT = 'it_IT';
    case en_GB = 'en_GB';
}
