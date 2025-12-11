<?php

namespace Wave8\Factotum\Base\Contracts\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Dtos\Api\QueryPaginationDto;

interface RoleServiceInterface
{
    public function create(Data $data): Model;

    public function read(int $id): Model;

    public function update(int $id, Data $data): Model;

    public function delete(int $id): void;

    public function filter(QueryPaginationDto $paginationDto): LengthAwarePaginator;
}
