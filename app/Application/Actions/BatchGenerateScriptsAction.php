<?php

namespace App\Application\Actions;

use App\Domain\Entities\TrendingTopic;
use App\Domain\Services\TrendingTopicService;
use Illuminate\Support\Facades\Log;

class BatchGenerateScriptsAction
{
    public function __construct(
        private TrendingTopicService $trendingService,
        private GenerateScriptAction $generateAction
    ) {}

    public function execute(int $limit = 5): array
    {
        $trending = $this->trendingService->getTopTrending($limit, onlyUnused: true);

        $results = [
            'generated' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($trending as $topic) {
            try {
                $result = $this->generateAction->execute(
                    topicTitle: $topic->title,
                    duration: 60
                );

                $generatedTopic = \App\Domain\Entities\Topic::find($result->topicId);
                $generatedTopic->update([
                    'trending_topic_id' => $topic->id,
                    'source_type' => $topic->source->type,
                ]);

                $topic->markAsUsed();

                $results['generated']++;

                Log::info("Batch generated script for trending topic", [
                    'topic_id' => $topic->id,
                    'script_id' => $result->scriptId,
                ]);
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'topic_id' => $topic->id,
                    'error' => $e->getMessage(),
                ];

                Log::error("Failed to generate script for trending topic", [
                    'topic_id' => $topic->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }
}
