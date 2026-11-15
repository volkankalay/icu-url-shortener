<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueApiTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required_without:email|prohibits:email|nullable|string|max:191',
            'email'    => 'required_without:username|nullable|string|email|max:191',
            'password' => 'required|string',
        ];
    }
}
