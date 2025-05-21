<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class AssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Role::where('name', $value)->exists()) {
                    $fail("The role {$value} does not exist.");
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'roles.required' => 'At least one role must be specified.',
            'roles.array' => 'Roles must be provided as an array.',
            'roles.*.required' => 'Each role must be specified.',
            'roles.*.string' => 'Each role must be a string.',
        ];
    }
}
