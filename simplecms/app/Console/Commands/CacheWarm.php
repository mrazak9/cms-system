<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class CacheWarm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up application caches for better performance';

    /**
     * The cache service instance
     */
    protected $cacheService;

    /**
     * Create a new command instance
     */
    public function __construct(CacheService $cacheService)
    {
        parent::__construct();
        $this->cacheService = $cacheService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Warming up application caches...');
        $this->newLine();

        $results = $this->cacheService->warmUp();

        // Display results in a table
        $tableData = [];
        foreach ($results as $cache => $status) {
            $tableData[] = [
                $cache,
                $status === 'success' ? '<info>✓ Success</info>' : '<error>✗ Failed</error>'
            ];
        }

        $this->table(['Cache', 'Status'], $tableData);

        $successCount = count(array_filter($results, fn($s) => $s === 'success'));
        $totalCount = count($results);

        $this->newLine();
        $this->info("Cache warming completed: {$successCount}/{$totalCount} caches warmed successfully.");

        return Command::SUCCESS;
    }
}
