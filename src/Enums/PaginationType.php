<?php

namespace Wave8\Factotum\Base\Enums;

use Wave8\Factotum\Base\Traits\ListCases;

enum PaginationType: string
{
    use ListCases;
    case STANDARD = 'standard';
    case SIMPLE = 'simple';
    case CURSOR = 'cursor';
}
