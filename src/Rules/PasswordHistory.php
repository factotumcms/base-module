<?php

namespace Wave8\Factotum\Base\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Wave8\Factotum\Base\Models\User;

class PasswordHistory implements ValidationRule
{
    protected User $user;

    protected int $validateLatest;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->user = User::findOrFail($id);
        $this->validateLatest = config('factotum_base.auth.password_validate_latest');
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $previousPasswords =
            $this->user->password_histories()->orderByDesc('created_at')->limit($this->validateLatest)->get();

        foreach ($previousPasswords as $previousPassword) {
            if (Hash::check($value, $previousPassword->password)) {
                $fail(__('validation.password.history_used'));
            }
        }
    }
}
