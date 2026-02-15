<?php

namespace App\Infrastructure\Scrapers;

use App\Domain\Services\Scrapers\TopicScraperInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsAPIScraper implements TopicScraperInterface
{
    private string $apiKey;
    private string $category;
    private int $pageSize;

    public function __construct(string $category = 'technology', int $pageSize = 20)
    {
        $this->apiKey = config('services.newsapi.api_key');
        $this->category = $category;
        $this->pageSize = $pageSize;
    }

    public function fetch(): array
    {
        if (empty($this->apiKey)) {
            Log::warning('NewsAPI key not configured');
            return [];
        }

        try {
            $response = Http::timeout(10)->get('https://newsapi.org/v2/top-headlines', [
                'apiKey' => $this->apiKey,
                'category' => $this->category,
                'language' => 'en',
                'pageSize' => $this->pageSize,
            ]);

            if (!$response->successful()) {
                Log::error('NewsAPI request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            }

            $data = $response->json();
            $topics = [];

            foreach ($data['articles'] ?? [] as $article) {
                $topics[] = [
                    'title' => $article['title'],
                    'description' => $article['description'] ?? $article['content'] ?? null,
                    'url' => $article['url'],
                    'upvotes' => null,
                    'comments' => null,
                    'category' => $this->category,
                    'metadata' => [
                        'source' => $article['source']['name'] ?? null,
                        'author' => $article['author'] ?? null,
                        'published_at' => $article['publishedAt'] ?? null,
                        'image_url' => $article['urlToImage'] ?? null,
                    ],
                ];
            }

            return $topics;
        } catch (\Exception $e) {
            Log::error('NewsAPI scraper error', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function getSourceType(): string
    {
        return 'newsapi';
    }
}
