<?php

namespace Wave8\Factotum\Base\Enums;

use Wave8\Factotum\Base\Traits\ListCases;

enum Disk: string
{
    use ListCases;
    case LOCAL = 'local';
    case PUBLIC = 'public';
    case S3 = 's3';
}
