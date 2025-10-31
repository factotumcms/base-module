<?php

namespace Wave8\Factotum\Base\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:strict'],
            'password' => ['required', 'string'],
            'username' => ['sometimes', 'string', 'unique:users,username'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
