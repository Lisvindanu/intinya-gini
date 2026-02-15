<?php

namespace App\Infrastructure\Scrapers;

use App\Domain\Services\Scrapers\TopicScraperInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleNewsIndonesiaScraper implements TopicScraperInterface
{
    private string $category;
    private int $limit;

    public function __construct(string $category = "NATION", int $limit = 20)
    {
        $this->category = $category;
        $this->limit = $limit;
    }

    public function fetch(): array
    {
        $topics = [];
        
        // Google News RSS untuk Indonesia
        $categories = [
            'NATION' => 'https://news.google.com/rss/topics/CAAqIQgKIhtDQkFTRGdvSUwyMHZNRFZ4ZERBU0FtbGtLQUFQAQ?hl=id&gl=ID&ceid=ID:id', // Berita Indonesia
            'TECHNOLOGY' => 'https://news.google.com/rss/topics/CAAqJggKIiBDQkFTRWdvSUwyMHZNRGRqTVhZU0FtbGtHZ0pKUkNnQVAB?hl=id&gl=ID&ceid=ID:id', // Teknologi
            'ENTERTAINMENT' => 'https://news.google.com/rss/topics/CAAqJggKIiBDQkFTRWdvSUwyMHZNREpxYW5RU0FtbGtHZ0pKUkNnQVAB?hl=id&gl=ID&ceid=ID:id', // Hiburan
            'SPORTS' => 'https://news.google.com/rss/topics/CAAqJggKIiBDQkFTRWdvSUwyMHZNRFp1ZEdvU0FtbGtHZ0pKUkNnQVAB?hl=id&gl=ID&ceid=ID:id', // Olahraga
        ];

        $rssUrl = $categories[$this->category] ?? $categories['NATION'];

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; IntinyaGini/1.0)'
                ])
                ->get($rssUrl);

            if (!$response->successful()) {
                Log::error('Google News scraper HTTP error', [
                    'status' => $response->status(),
                    'category' => $this->category
                ]);
                return [];
            }

            $xml = simplexml_load_string($response->body());
            
            if (!$xml) {
                Log::error('Google News scraper XML parse error');
                return [];
            }

            $count = 0;
            foreach ($xml->channel->item as $item) {
                if ($count >= $this->limit) break;

                $title = (string) $item->title;
                $link = (string) $item->link;
                $pubDate = (string) $item->pubDate;
                $description = (string) ($item->description ?? '');
                
                // Extract source from title (Google News format: "Title - Source")
                $source = 'Google News';
                if (preg_match('/ - (.+)$/', $title, $matches)) {
                    $source = $matches[1];
                    $title = preg_replace('/ - .+$/', '', $title);
                }

                $topics[] = [
                    'title' => trim($title),
                    'description' => strip_tags($description),
                    'url' => $link,
                    'upvotes' => 0, // Google News doesn't have upvotes
                    'comments' => 0,
                    'category' => $this->category,
                    'metadata' => [
                        'source' => $source,
                        'pub_date' => $pubDate,
                        'category' => $this->category,
                    ],
                ];

                $count++;
            }

            Log::info('Google News scraper success', [
                'category' => $this->category,
                'fetched' => count($topics)
            ]);

        } catch (\Exception $e) {
            Log::error('Google News scraper error', [
                'error' => $e->getMessage(),
                'category' => $this->category
            ]);
        }

        return $topics;
    }

    public function getSourceType(): string
    {
        return 'google_news';
    }
}
