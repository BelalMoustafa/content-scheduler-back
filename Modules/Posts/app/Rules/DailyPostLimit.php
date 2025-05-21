<?php

namespace Modules\Posts\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Posts\Repositories\PostRepositoryInterface;

class DailyPostLimit implements Rule
{
    private const MAX_POSTS_PER_DAY = 10;
    private PostRepositoryInterface $postRepository;

    public function __construct(private $user)
    {
        $this->postRepository = app(PostRepositoryInterface::class);
    }

    public function passes($attribute, $value): bool
    {
        $date = date('Y-m-d', strtotime($value));
        $count = $this->postRepository->getScheduledPostsCount($this->user, $date);

        if ($count >= self::MAX_POSTS_PER_DAY) {
            $this->message = "You have reached the maximum limit of " . self::MAX_POSTS_PER_DAY . " scheduled posts per day.";
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->message ?? 'Daily post limit exceeded.';
    }
}
