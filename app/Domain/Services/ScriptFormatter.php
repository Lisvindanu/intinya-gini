<?php

namespace App\Domain\Services;

class ScriptFormatter
{
    private const TAGLINE = 'Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang.';

    public function __construct(
        private ScriptValidator $validator
    ) {}

    public function parse(string $aiResponse): array
    {
        $cleaned = $this->cleanJsonResponse($aiResponse);

        try {
            $data = json_decode($cleaned, true, 512, JSON_THROW_ON_ERROR);

            $parsed = [
                'hook' => $data['hook'] ?? '',
                'content' => $data['content'] ?? '',
                'key_points' => $data['key_points'] ?? [],
                'title' => $data['title'] ?? '',
                'caption' => $data['caption'] ?? '',
            ];

            return $this->polishOutput($parsed);

        } catch (\JsonException $e) {
            return $this->fallbackParse($aiResponse);
        }
    }

    private function polishOutput(array $data): array
    {
        if (!empty($data['content']) && !str_starts_with($data['content'], self::TAGLINE)) {
            $data['content'] = self::TAGLINE . ' ' . $data['content'];
        }

        if (!empty($data['caption']) && !str_contains($data['caption'], '#IntinyaGini')) {
            $data['caption'] = $data['caption'] . ' #IntinyaGini';
        }

        if (!empty($data['hook'])) {
            $data['hook'] = $this->cleanHook($data['hook']);
        }

        if (empty($data['key_points'])) {
            $data['key_points'] = ['Informasi tidak lengkap'];
        }

        return $data;
    }

    private function cleanHook(string $hook): string
    {
        $hook = preg_replace('/\b(\w+)\s+\1\b/i', '$1', $hook);
        $hook = preg_replace('/\s+/', ' ', $hook);
        return trim($hook);
    }

    private function cleanJsonResponse(string $response): string
    {
        $response = trim($response);

        if (preg_match('/```json\s*(.*?)\s*```/s', $response, $matches)) {
            return $matches[1];
        }

        if (preg_match('/```\s*(.*?)\s*```/s', $response, $matches)) {
            return $matches[1];
        }

        if (preg_match('/\{.*\}/s', $response, $matches)) {
            return $matches[0];
        }

        return $response;
    }

    private function fallbackParse(string $response): array
    {
        return [
            'hook' => 'Hook tidak tersedia',
            'content' => self::TAGLINE . ' ' . $response,
            'key_points' => ['Parsing error occurred'],
            'title' => 'TL;DR Content',
            'caption' => 'Konten TL;DR otomatis #IntinyaGini',
        ];
    }

    public function formatForExport(array $scriptData, string $format = 'text'): string
    {
        return match ($format) {
            'srt' => $this->formatSRT($scriptData),
            'markdown' => $this->formatMarkdown($scriptData),
            default => $this->formatText($scriptData),
        };
    }

    private function formatText(array $data): string
    {
        $points = implode("\n", array_map(fn($p, $i) => ($i + 1) . ". {$p}", $data['key_points'], array_keys($data['key_points'])));

        return <<<TEXT
HOOK:
{$data['hook']}

SCRIPT:
{$data['content']}

POIN INTI:
{$points}

JUDUL:
{$data['title']}

CAPTION:
{$data['caption']}
TEXT;
    }

    private function formatMarkdown(array $data): string
    {
        $points = implode("\n", array_map(fn($p, $i) => ($i + 1) . ". {$p}", $data['key_points'], array_keys($data['key_points'])));

        return <<<MARKDOWN
# {$data['title']}

## Hook
{$data['hook']}

## Script
{$data['content']}

## Poin Inti
{$points}

## Caption
{$data['caption']}
MARKDOWN;
    }

    private function formatSRT(array $data): string
    {
        $script = $data['content'];
        $words = explode(' ', $script);
        $wordsPerSubtitle = 5;
        $chunks = array_chunk($words, $wordsPerSubtitle);

        $srt = '';
        $index = 1;
        $currentTime = 0;

        foreach ($chunks as $chunk) {
            $text = implode(' ', $chunk);
            $duration = count($chunk) * 0.4;

            $startTime = $this->formatSRTTime($currentTime);
            $endTime = $this->formatSRTTime($currentTime + $duration);

            $srt .= "{$index}\n";
            $srt .= "{$startTime} --> {$endTime}\n";
            $srt .= "{$text}\n\n";

            $currentTime += $duration;
            $index++;
        }

        return $srt;
    }

    private function formatSRTTime(float $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = floor($seconds % 60);
        $millis = floor(($seconds - floor($seconds)) * 1000);

        return sprintf('%02d:%02d:%02d,%03d', $hours, $minutes, $secs, $millis);
    }
}
