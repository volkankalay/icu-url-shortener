<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'site'        => 'required|string|url:http,https|max:191',
            'title'       => 'nullable|string|max:191',
            'keywords'    => 'nullable|string|max:191',
            'description' => 'nullable|string|max:191',
            'header_code' => 'nullable|string',
            'footer_code' => 'nullable|string',
            'register'    => 'required|boolean',
            'logo'        => 'nullable|image|max:2048',
            'favicon'     => 'nullable|image|max:2048',
        ];
    }
}
