<?php

namespace Modules\Posts\Console\Commands;

use Illuminate\Console\Command;
use Modules\Posts\Jobs\PublishScheduledPosts;

class PublishScheduledPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all scheduled posts that are due';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting scheduled posts publishing...');

        try {
            PublishScheduledPosts::dispatch();
            $this->info('Scheduled posts publishing job dispatched successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to dispatch scheduled posts publishing job: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
