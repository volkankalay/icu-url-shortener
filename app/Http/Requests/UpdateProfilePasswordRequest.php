<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePasswordRequest extends FormRequest
{
    protected $errorBag = 'passwordChange';

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['bail', 'required', 'string', 'current_password'],
            'password'         => ['bail', 'required', 'string', 'min:8', 'regex:/\A[^\x00]{8,72}\z/', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => __('auth.current_password_invalid'),
            'password.regex'                    => __('auth.password_invalid'),
        ];
    }
}
