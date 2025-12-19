<?php

namespace Wave8\Factotum\Base\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class PasswordHistory implements ValidationRule
{
    protected int $validateLatest;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->validateLatest = config('factotum_base.auth.password_validate_latest');
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $previousPasswords =
            request()->user()->passwordHistories()->orderByDesc('created_at')->limit($this->validateLatest)->get();

        foreach ($previousPasswords as $previousPassword) {
            if (Hash::check($value, $previousPassword->password)) {
                $fail(__('validation.password.history_used'));
            }
        }
    }
}
