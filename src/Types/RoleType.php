<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ListCases;

enum RoleType: string
{
    use ListCases;
    case ADMIN = 'admin';
    case EDITOR = 'editor';
}
