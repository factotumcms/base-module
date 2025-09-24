<?php

namespace Wave8\Factotum\Base\Enums;

use Wave8\Factotum\Base\Traits\ListCases;

enum Role: string
{
    use ListCases;
    case ADMIN = 'admin';
    case EDITOR = 'editor';
}
