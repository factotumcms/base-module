<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Wave8\Factotum\Base\Policies\UserPolicy;

#[UsePolicy(UserPolicy::class)]
final class User extends \Wave8\Factotum\Base\Models\User
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
}
