<?php

namespace Wave8\Factotum\Base\Contracts\Api;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

interface UserServiceInterface
{
    public function create(Data $data): Model;

    public function read(int $id): Model;

    public function update(int $id, Data $data): Model;

    public function delete(int $id): void;

    public function filter(): LengthAwarePaginator;
}
