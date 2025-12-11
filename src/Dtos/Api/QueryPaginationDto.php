<?php

namespace Wave8\Factotum\Base\Dtos\Api;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class QueryPaginationDto extends Data
{
    public function __construct(
        public Optional|null|int $perPage = null,
        public Optional|null|int $page = null,
    ) {}
}
