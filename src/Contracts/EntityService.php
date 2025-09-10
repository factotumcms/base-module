<?php

namespace Wave8\Factotum\Base\Contracts;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

interface EntityService
{
    public function create(Data $data): Model;
}
