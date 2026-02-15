<?php

namespace App\Console\Commands;

use App\Domain\Entities\TopicSource;
use App\Domain\Services\TrendingTopicService;
use App\Infrastructure\Scrapers\NewsAPIScraper;
use App\Infrastructure\Scrapers\RedditScraper;
use Illuminate\Console\Command;

class FetchTrendingTopics extends Command
{
    protected $signature = 'trending:fetch {--force : Force fetch even if interval not reached}';

    protected $description = 'Fetch trending topics from configured sources';

    public function __construct(
        private TrendingTopicService $trendingService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Fetching trending topics...');

        $sources = TopicSource::where('is_active', true)->get();

        if ($sources->isEmpty()) {
            $this->error('No active sources found. Run: php artisan sources:seed');
            return self::FAILURE;
        }

        $totalFetched = 0;

        foreach ($sources as $source) {
            if (!$this->option('force') && !$source->shouldFetch()) {
                $this->warn("⏭ Skipping {$source->name} (last fetched {$source->last_fetched_at->diffForHumans()})");
                continue;
            }

            $this->info("📡 Fetching from {$source->name}...");

            $scraper = $this->getScraperForSource($source);

            if (!$scraper) {
                $this->error("No scraper available for {$source->type}");
                continue;
            }

            $count = $this->trendingService->fetchFromSource($source, $scraper);
            $totalFetched += $count;

            $this->info("✓ Fetched {$count} topics from {$source->name}");
        }

        $this->info("✓ Total fetched: {$totalFetched} trending topics");

        $this->info('♻ Recalculating scores...');
        $updated = $this->trendingService->recalculateScores();
        $this->info("✓ Updated {$updated} scores");

        $this->info('🗑 Cleaning old topics...');
        $deleted = $this->trendingService->cleanOldTopics(7);
        $this->info("✓ Deleted {$deleted} old topics");

        return self::SUCCESS;
    }

    private function getScraperForSource(TopicSource $source)
    {
        return match ($source->type) {
            'reddit' => new RedditScraper(
                $source->config['subreddits'] ?? null,
                $source->config['limit'] ?? 25
            ),
            'newsapi' => new NewsAPIScraper(
                $source->config['category'] ?? 'technology',
                $source->config['page_size'] ?? 20
            ),
            'google_news' => new \App\Infrastructure\Scrapers\GoogleNewsIndonesiaScraper(
                $source->config['category'] ?? 'NATION',
                $source->config['limit'] ?? 20
            ),
            default => null,
        };
    }
}
