<?php

namespace Wave8\Factotum\Base\Http\Requests\Api\User;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:strict'],
            'password' => ['required', 'string'],
            'username' => ['string', 'nullable', 'unique:users,username'],
            'first_name' => ['string', 'nullable'],
            'last_name' => ['string', 'nullable'],
            'is_active' => ['boolean', 'nullable'],
        ];
    }
}
