<?php

namespace Modules\Platforms\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlatformRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:50', 'unique:platforms,type'],
        ];

        // If updating, exclude current platform from unique check
        if ($this->isMethod('PUT')) {
            $rules['type'] = ['required', 'string', 'max:50', 'unique:platforms,type,' . $this->route('id')];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The platform name is required.',
            'name.string' => 'The platform name must be a string.',
            'name.max' => 'The platform name cannot exceed 255 characters.',
            'type.required' => 'The platform type is required.',
            'type.string' => 'The platform type must be a string.',
            'type.max' => 'The platform type cannot exceed 50 characters.',
            'type.unique' => 'This platform type already exists.',
        ];
    }
}
