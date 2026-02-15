<?php

namespace App\Application\Actions;

use App\Application\DTO\ScriptResultDTO;
use App\Domain\Entities\Generation;
use App\Domain\Entities\Script;
use App\Domain\Services\TLDRGeneratorService;
use Illuminate\Support\Facades\DB;

class RegenerateScriptAction
{
    public function __construct(
        private TLDRGeneratorService $generatorService
    ) {}

    public function execute(int $scriptId, ?string $promptName = null): ScriptResultDTO
    {
        return DB::transaction(function () use ($scriptId, $promptName) {
            $originalScript = Script::with('topic')->findOrFail($scriptId);
            
            // Determine parent (could be the original or already a variation)
            $parentId = $originalScript->getRootScriptId();
            $nextVersion = $originalScript->getNextVersion();

            // Generate new script
            $result = $this->generatorService->generate($originalScript->topic, $promptName);

            // Create new variation
            $newScript = Script::create([
                'topic_id' => $originalScript->topic_id,
                'parent_script_id' => $parentId,
                'version' => $nextVersion,
                'variation_type' => 'regenerate',
                'generation_config' => [
                    'prompt_name' => $promptName,
                    'regenerated_from' => $scriptId,
                    'timestamp' => now()->toIso8601String(),
                ],
                'hook' => $result['script_data']['hook'],
                'content' => $result['script_data']['content'],
                'key_points' => $result['script_data']['key_points'],
                'title' => $result['script_data']['title'],
                'caption' => $result['script_data']['caption'],
            ]);

            // Log generation
            Generation::create([
                'topic_id' => $originalScript->topic_id,
                'model_used' => $result['metadata']['model_used'],
                'tokens_used' => $result['metadata']['tokens_used'],
                'response_time' => $result['metadata']['response_time'],
            ]);

            return new ScriptResultDTO(
                topicId: $originalScript->topic_id,
                scriptId: $newScript->id,
                hook: $newScript->hook,
                content: $newScript->content,
                keyPoints: $newScript->key_points,
                title: $newScript->title,
                caption: $newScript->caption,
                metadata: array_merge($result['metadata'], [
                    'is_variation' => true,
                    'version' => $nextVersion,
                    'parent_id' => $parentId,
                ])
            );
        });
    }
}
