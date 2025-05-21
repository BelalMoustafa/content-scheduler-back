<?php

namespace Modules\Platforms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Platforms\Services\PlatformService;

class TogglePlatformRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform_id' => [
                'required',
                'integer',
                'exists:platforms,id'
            ],
            'is_active' => [
                'required',
                'boolean'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'platform_id.required' => 'The platform ID is required.',
            'platform_id.integer' => 'The platform ID must be an integer.',
            'platform_id.exists' => 'The selected platform does not exist.',
            'is_active.required' => 'The active status is required.',
            'is_active.boolean' => 'The active status must be true or false.'
        ];
    }
}
