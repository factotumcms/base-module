<?php

namespace Wave8\Factotum\Base\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

interface CrudService
{
    public function create(Data $data): Model;
    public function getAll();
}
