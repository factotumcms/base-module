<?php

namespace Wave8\Factotum\Base\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Wave8\Factotum\Base\Policies\PermissionPolicy;

#[UsePolicy(PermissionPolicy::class)]
class Permission extends \Spatie\Permission\Models\Permission {}
