<?php

namespace App\Console\Commands;

use App\Jobs\PublishScheduledContent as PublishScheduledContentJob;
use Illuminate\Console\Command;

class PublishScheduledContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled posts and pages that are due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for scheduled content to publish...');

        // Dispatch the job
        PublishScheduledContentJob::dispatch();

        $this->info('Scheduled content publishing job has been dispatched.');

        return Command::SUCCESS;
    }
}
