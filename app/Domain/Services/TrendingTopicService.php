<?php

namespace App\Domain\Services;

use App\Domain\Entities\TopicSource;
use App\Domain\Entities\TrendingTopic;
use App\Domain\Services\Scrapers\TopicScraperInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrendingTopicService
{
    public function fetchFromSource(TopicSource $source, TopicScraperInterface $scraper): int
    {
        try {
            $topics = $scraper->fetch();
            $count = 0;

            DB::transaction(function () use ($source, $topics, &$count) {
                foreach ($topics as $topicData) {
                    $existing = TrendingTopic::where('source_id', $source->id)
                        ->where('title', $topicData['title'])
                        ->where('fetched_at', '>=', now()->subDay())
                        ->first();

                    if ($existing) {
                        continue;
                    }

                    $trending = TrendingTopic::create([
                        'source_id' => $source->id,
                        'title' => $topicData['title'],
                        'description' => $topicData['description'],
                        'url' => $topicData['url'],
                        'upvotes' => $topicData['upvotes'],
                        'comments' => $topicData['comments'],
                        'category' => $topicData['category'],
                        'metadata' => $topicData['metadata'],
                        'fetched_at' => now(),
                        'score' => 0,
                    ]);

                    $trending->update(['score' => $trending->calculateScore()]);
                    $count++;
                }

                $source->update(['last_fetched_at' => now()]);
            });

            Log::info("Fetched {$count} trending topics from {$source->name}");

            return $count;
        } catch (\Exception $e) {
            Log::error("Error fetching from {$source->name}", [
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }

    public function getTopTrending(int $limit = 20, bool $onlyUnused = true)
    {
        $query = TrendingTopic::with('source')
            ->where('fetched_at', '>=', now()->subDays(3))
            ->orderBy('score', 'desc');

        if ($onlyUnused) {
            $query->where('is_used', false);
        }

        return $query->paginate($limit);
    }

    public function cleanOldTopics(int $daysOld = 7): int
    {
        return TrendingTopic::where('fetched_at', '<', now()->subDays($daysOld))->delete();
    }

    public function recalculateScores(): int
    {
        $topics = TrendingTopic::where('fetched_at', '>=', now()->subDays(3))->get();
        $count = 0;

        foreach ($topics as $topic) {
            $newScore = $topic->calculateScore();
            if ($topic->score !== $newScore) {
                $topic->update(['score' => $newScore]);
                $count++;
            }
        }

        return $count;
    }
}
