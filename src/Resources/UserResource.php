<?php

namespace Wave8\Factotum\Base\Resources;

use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Resource;

class UserResource extends Resource
{
    public function __construct(
        public string $email,
        public \DateTime $created_at,
        public ?string $first_name,
        public ?string $last_name,
        public ?string $username,
        public ?Collection $roles
    ) {}
}
