<?php

namespace App\Application\Actions;

use App\Application\DTO\ScriptResultDTO;
use App\Domain\Entities\Generation;
use App\Domain\Entities\Script;
use App\Domain\Entities\Topic;
use App\Domain\Services\TLDRGeneratorService;
use Illuminate\Support\Facades\DB;

class GenerateScriptAction
{
    public function __construct(
        private TLDRGeneratorService $generatorService
    ) {}

    public function execute(string $topicTitle, int $duration = 60): ScriptResultDTO
    {
        return DB::transaction(function () use ($topicTitle, $duration) {
            $topic = Topic::create([
                'title' => $topicTitle,
                'duration' => $duration,
            ]);

            $result = $this->generatorService->generate($topic);

            $script = Script::create([
                'topic_id' => $topic->id,
                'hook' => $result['script_data']['hook'],
                'content' => $result['script_data']['content'],
                'key_points' => $result['script_data']['key_points'],
                'title' => $result['script_data']['title'],
                'caption' => $result['script_data']['caption'],
            ]);

            Generation::create([
                'topic_id' => $topic->id,
                'model_used' => $result['metadata']['model_used'],
                'tokens_used' => $result['metadata']['tokens_used'],
                'response_time' => $result['metadata']['response_time'],
            ]);

            return new ScriptResultDTO(
                topicId: $topic->id,
                scriptId: $script->id,
                hook: $script->hook,
                content: $script->content,
                keyPoints: $script->key_points,
                title: $script->title,
                caption: $script->caption,
                metadata: $result['metadata']
            );
        });
    }
}
