<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Model;
use Wave8\Factotum\Base\Models\PasswordHistory;
use Wave8\Factotum\Base\Models\User;

class PasswordHistoryService
{
    public function __construct(public readonly PasswordHistory $passwordHistory) {}
}
