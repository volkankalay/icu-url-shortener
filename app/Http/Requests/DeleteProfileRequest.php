<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteProfileRequest extends FormRequest
{
    protected $errorBag = 'accountDeletion';

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['bail', 'required', 'string', 'current_password'],
            'confirm_delete'   => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => __('auth.current_password_invalid'),
            'confirm_delete.accepted'           => __('auth.confirm_delete_required'),
        ];
    }
}
