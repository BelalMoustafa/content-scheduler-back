<?php

namespace Modules\Posts\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Platforms\Models\Platform;

class PlatformCharacterLimit implements Rule
{
    private array $platformIds;
    private array $limits = [
        'twitter' => 280,
        'linkedin' => 1300,
        'instagram' => null, // unlimited
        'facebook' => 63206
    ];

    public function __construct(array $platformIds)
    {
        $this->platformIds = $platformIds;
    }

    public function passes($attribute, $value): bool
    {
        $platforms = Platform::whereIn('id', $this->platformIds)->get();

        foreach ($platforms as $platform) {
            $limit = $this->limits[$platform->type] ?? null;

            if ($limit !== null && strlen($value) > $limit) {
                $this->message = "Content exceeds {$platform->name}'s character limit of {$limit} characters.";
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return $this->message ?? 'Content exceeds platform character limits.';
    }
}
