<?php

namespace App\Infrastructure\Scrapers;

use App\Domain\Services\Scrapers\TopicScraperInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RedditScraper implements TopicScraperInterface
{
    private array $subreddits;
    private int $limit;

    public function __construct(array $subreddits = null, int $limit = 25)
    {
        $this->subreddits = $subreddits ?? config('scrapers.reddit.subreddits', ['programming', 'technology', 'webdev']);
        $this->limit = $limit;
    }

    public function fetch(): array
    {
        $topics = [];

        foreach ($this->subreddits as $subreddit) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'IntiinyaGini:v1.0 (by /u/YourUsername)',
                    ])
                    ->get("https://www.reddit.com/r/{$subreddit}/hot.json", [
                        'limit' => $this->limit,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    foreach ($data['data']['children'] ?? [] as $post) {
                        $postData = $post['data'];

                        $topics[] = [
                            'title' => $postData['title'],
                            'description' => $postData['selftext'] ?? null,
                            'url' => 'https://reddit.com' . $postData['permalink'],
                            'upvotes' => $postData['ups'] ?? 0,
                            'comments' => $postData['num_comments'] ?? 0,
                            'category' => $subreddit,
                            'metadata' => [
                                'subreddit' => $postData['subreddit'],
                                'author' => $postData['author'],
                                'created_utc' => $postData['created_utc'],
                                'flair' => $postData['link_flair_text'] ?? null,
                            ],
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error("Reddit scraper error for r/{$subreddit}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $topics;
    }

    public function getSourceType(): string
    {
        return 'reddit';
    }
}
