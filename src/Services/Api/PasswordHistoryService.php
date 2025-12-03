<?php

namespace Wave8\Factotum\Base\Services\Api;

use Wave8\Factotum\Base\Models\PasswordHistory;

class PasswordHistoryService
{
    public function __construct(public readonly PasswordHistory $passwordHistory) {}
}
