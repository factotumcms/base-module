<?php

namespace Wave8\Factotum\Base\Enum;

use Wave8\Factotum\Base\Traits\ListCases;

enum Role: string
{
    use ListCases;
    case ADMIN = 'admin';
    case EDITOR = 'editor';
}
