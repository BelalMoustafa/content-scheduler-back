<?php

namespace Modules\Posts\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Posts\Services\PostService;

class PublishScheduledPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(PostService $postService): void
    {
        try {
            Log::info('Starting scheduled posts publishing job');

            $posts = $postService->getDuePosts();

            foreach ($posts as $post) {
                try {
                    $postService->publishPost($post);
                } catch (\Exception $e) {
                    Log::error('Failed to publish post', [
                        'post_id' => $post->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Completed scheduled posts publishing job', [
                'processed_count' => $posts->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Scheduled posts publishing job failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
