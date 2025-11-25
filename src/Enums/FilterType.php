<?php

namespace Wave8\Factotum\Base\Enums;

use Wave8\Factotum\Base\Traits\ListCases;

enum FilterType: string
{
    use ListCases;

    case EXACT = 'exact';
    case DYNAMIC = 'dynamic';
    case LIKE = 'like';
}
