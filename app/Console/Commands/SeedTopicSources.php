<?php

namespace App\Console\Commands;

use App\Domain\Entities\TopicSource;
use Illuminate\Console\Command;

class SeedTopicSources extends Command
{
    protected $signature = 'sources:seed';

    protected $description = 'Seed default topic sources (Reddit, NewsAPI)';

    public function handle(): int
    {
        $this->info('Seeding topic sources...');

        TopicSource::updateOrCreate(
            ['type' => 'reddit', 'name' => 'Reddit Programming'],
            [
                'is_active' => true,
                'fetch_interval' => 3600,
                'config' => [
                    'subreddits' => config('scrapers.reddit.subreddits'),
                    'limit' => config('scrapers.reddit.limit'),
                ],
            ]
        );

        $this->info('✓ Reddit source created');

        if (config('services.newsapi.api_key')) {
            TopicSource::updateOrCreate(
                ['type' => 'newsapi', 'name' => 'News API Technology'],
                [
                    'is_active' => true,
                    'fetch_interval' => 7200,
                    'config' => [
                        'category' => 'technology',
                        'page_size' => 20,
                    ],
                ]
            );

            $this->info('✓ NewsAPI source created');
        } else {
            $this->warn('! NewsAPI key not configured, skipping');
        }

        $this->info('Topic sources seeded successfully!');

        return self::SUCCESS;
    }
}
