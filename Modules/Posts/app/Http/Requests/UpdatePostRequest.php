<?php

namespace Modules\Posts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Posts\Models\Post;
use Modules\Posts\Rules\DailyPostLimit;
use Modules\Posts\Rules\PlatformCharacterLimit;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = Post::find($this->route('id'));
        return $post && $post->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'content' => ['sometimes', 'required', 'string', new PlatformCharacterLimit($this->input('platforms', []))],
            'platforms' => ['sometimes', 'required', 'array', 'min:1'],
            'platforms.*' => ['required', 'integer', 'exists:platforms,id'],
            'scheduled_time' => [
                'nullable',
                'date',
                'after:now',
                new DailyPostLimit($this->user())
            ],
            'media_urls' => ['nullable', 'array'],
            'media_urls.*' => ['url', 'max:2048'],
            'status' => ['nullable', 'string', 'in:draft,scheduled']
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'content.required' => 'Post content is required',
            'platforms.required' => 'At least one platform must be selected',
            'platforms.min' => 'At least one platform must be selected',
            'platforms.*.exists' => 'One or more selected platforms are invalid',
            'scheduled_time.after' => 'Scheduled time must be in the future',
            'media_urls.*.url' => 'Invalid media URL format',
            'media_urls.*.max' => 'Media URL is too long',
            'status.in' => 'Invalid post status'
        ];
    }
}
