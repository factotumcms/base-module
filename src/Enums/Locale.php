<?php

namespace Wave8\Factotum\Base\Enums;

use Wave8\Factotum\Base\Traits\ListCases;

enum Locale: string
{
    use ListCases;

    case IT = 'it';
    case EN = 'en';
}
