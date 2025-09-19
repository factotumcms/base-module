<?php

namespace Wave8\Factotum\Base\Enum;

use Wave8\Factotum\Base\Traits\ListCases;

enum Locale: string
{
    use ListCases;

    case it_IT = 'it_IT';
    case en_GB = 'en_GB';
}
