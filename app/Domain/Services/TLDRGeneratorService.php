<?php

namespace App\Domain\Services;

use App\Domain\Entities\Prompt;
use App\Domain\Entities\Topic;
use App\Infrastructure\AI\AIClient;

class TLDRGeneratorService
{
    public function __construct(
        private AIClient $aiClient,
        private ScriptFormatter $formatter
    ) {}

    public function generate(Topic $topic, ?string $promptName = null): array
    {
        $startTime = microtime(true);

        // Auto-detect prompt type if not specified
        if (!$promptName) {
            $promptName = $this->detectPromptType($topic->title);
        }

        $prompt = $this->buildPromptFromDB($topic, $promptName);
        $response = $this->aiClient->generate($prompt);

        $responseTime = (microtime(true) - $startTime) * 1000;

        $parsed = $this->formatter->parse($response['content']);

        return [
            'script_data' => $parsed,
            'metadata' => [
                'model_used' => $response['model'],
                'tokens_used' => $response['tokens'] ?? null,
                'response_time' => $responseTime,
                'prompt_used' => $promptName,
            ],
        ];
    }

    private function detectPromptType(string $title): string
    {
        $title = strtolower($title);

        // Detect drama/gossip keywords
        $dramaKeywords = ['pelakor', 'selingkuh', 'dibully', 'meludahi', 'skandal', 'gosip', 'drama', 'perceraian', 'respon'];
        foreach ($dramaKeywords as $keyword) {
            if (str_contains($title, $keyword)) {
                return 'tldr_drama';
            }
        }

        // Detect tech keywords
        $techKeywords = ['ai', 'teknologi', 'aplikasi', 'software', 'coding', 'programming', 'developer', 'inovasi', 'algorithm', 'data'];
        foreach ($techKeywords as $keyword) {
            if (str_contains($title, $keyword)) {
                return 'tldr_tech';
            }
        }

        // Default to general tldr_v1
        return 'tldr_v1';
    }

    private function buildPromptFromDB(Topic $topic, string $promptName): string
    {
        $promptTemplate = Prompt::getActive($promptName);

        if (!$promptTemplate) {
            // Fallback to v1 if prompt not found
            $promptTemplate = Prompt::getActive('tldr_v1');
        }

        if (!$promptTemplate) {
            // Final fallback to hardcoded
            return $this->buildFallbackPrompt($topic);
        }

        return $promptTemplate->render([
            'title' => $topic->title,
            'duration' => $topic->duration,
        ]);
    }

    private function buildFallbackPrompt(Topic $topic): string
    {
        // Fallback if DB prompts not available
        return <<<PROMPT
Topik: {$topic->title}
Durasi: {$topic->duration} detik

Buatkan TL;DR script dalam format JSON dengan struktur:
{
    "hook": "...",
    "content": "Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang. ...",
    "key_points": [...],
    "title": "...",
    "caption": "... #IntinyaGini"
}
PROMPT;
    }
}
