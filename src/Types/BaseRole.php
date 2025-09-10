<?php

namespace Wave8\Factotum\Base\Types;

use Wave8\Factotum\Base\Traits\ConstantsList;

abstract class BaseRole
{
    use ConstantsList;

    const string ADMIN = 'admin';

    const string EDITOR = 'editor';
}
