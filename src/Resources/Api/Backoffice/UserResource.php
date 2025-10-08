<?php

namespace Wave8\Factotum\Base\Resources\Api\Backoffice;

use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Resource;

class UserResource extends Resource
{
    public function __construct(
        public int $id,
        public string $email,
        public ?string $first_name,
        public ?string $last_name,
        public ?string $username,
        public \DateTime $created_at,
        public ?Collection $roles,
        public ?MediaResource $profile_picture = null,
    ) {}
}
