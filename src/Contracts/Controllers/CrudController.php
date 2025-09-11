<?php

namespace Wave8\Factotum\Base\Contracts\Controllers;

use Illuminate\Http\JsonResponse;

interface CrudController
{
    public function all(): JsonResponse;
    public function read(int $id): JsonResponse;

}
