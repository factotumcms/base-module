<?php

namespace Wave8\Factotum\Base\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['prohibits:username', 'required', 'email:rfc'],
            'password' => ['required', 'string'],
            'username' => ['prohibits:email', 'sometimes', 'required', 'string'],
        ];
    }
}
