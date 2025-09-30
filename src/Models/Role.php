<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Wave8\Factotum\Base\Policies\RolePolicy;

#[UsePolicy(RolePolicy::class)]
class Role extends \Spatie\Permission\Models\Role {
    protected $fillable = [
        'name',
        'guard_name',
    ];
}
