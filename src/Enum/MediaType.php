<?php

namespace Wave8\Factotum\Base\Enum;

use Wave8\Factotum\Base\Traits\ListCases;

enum MediaType: string
{
    use ListCases;
    case IMAGE = 'image';
    case VIDEO = 'video';
    case AUDIO = 'audio';
    case PDF = 'pdf';
    case XLS = 'xls';
    case XLSX = 'xlsx';
    case CSV = 'csv';
    case WORD = 'word';
}
