<?php

namespace Wave8\Factotum\Base\Services\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;
use Wave8\Factotum\Base\Contracts\Api\RoleServiceInterface;
use Wave8\Factotum\Base\Models\PasswordHistory;
use Wave8\Factotum\Base\Models\Role;
use Wave8\Factotum\Base\Models\User;

class PasswordHistoryService
{
    public function __construct(public readonly PasswordHistory $passwordHistory) {}

    public function getCurrentPasswordForUser(User $user): ?Model
    {
        $passwordHistory = $user->password_histories;

        if ($passwordHistory === null || !hash_equals($user->password, $passwordHistory->password)) {
            return null;
        }

        return $passwordHistory;
    }

    public function isCurrentPasswordExpired(User $user): bool
    {
        if ($user->last_login_at === null) {
            return false;
        }

        $currentPassword = $this->getCurrentPasswordForUser($user);

        return $currentPassword->expires_at->lessThanOrEqualTo($user->last_login_at);
    }
}
