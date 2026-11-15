<?php

namespace App\Http\Requests;

use App\Services\ReservedShortCodes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShortCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(ReservedShortCodes $reservedCodes): array
    {
        return [
            'shortold' => 'required|string|alpha_dash|max:191',
            'shortnew' => [
                'bail', 'required', 'string', 'alpha_dash:ascii', 'min:4', 'max:191',
                'different:shortold',
                Rule::notIn($reservedCodes->all()),
                Rule::unique('links', 'short'),
            ],
        ];
    }
}
