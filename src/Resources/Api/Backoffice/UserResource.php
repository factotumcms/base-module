<?php

namespace Wave8\Factotum\Base\Resources\Api\Backoffice;

use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Resource;

class UserResource extends Resource
{
    /**
     * Create a new UserResource instance representing a backoffice user.
     *
     * @param  int  $id  The user's unique identifier.
     * @param  string  $email  The user's email address.
     * @param  string|null  $first_name  The user's first name, or null if not provided.
     * @param  string|null  $last_name  The user's last name, or null if not provided.
     * @param  string|null  $username  The user's username, or null if not provided.
     * @param  \DateTime  $created_at  Timestamp when the user was created.
     * @param  \Illuminate\Support\Collection|null  $roles  Collection of role representations assigned to the user, or null.
     * @param  MediaResource|null  $profile_picture  Optional profile picture resource, or null.
     */
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
