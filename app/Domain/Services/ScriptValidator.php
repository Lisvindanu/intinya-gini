<?php

namespace App\Domain\Services;

class ScriptValidator
{
    private const REQUIRED_TAGLINE = 'Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang.';
    private const MIN_CONTENT_LENGTH = 50;
    private const MAX_HOOK_LENGTH = 150;
    
    private array $errors = [];
    private array $warnings = [];

    public function validate(array $scriptData): array
    {
        $this->errors = [];
        $this->warnings = [];

        $this->validateStructure($scriptData);
        $this->validateHook($scriptData['hook'] ?? '');
        $this->validateContent($scriptData['content'] ?? '');
        $this->validateKeyPoints($scriptData['key_points'] ?? []);
        $this->validateCaption($scriptData['caption'] ?? '');

        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'warnings' => $this->warnings,
        ];
    }

    public function fix(array $scriptData): array
    {
        // Auto-fix common issues
        $fixed = $scriptData;

        // Fix 1: Ensure tagline
        if (!str_starts_with($fixed['content'], self::REQUIRED_TAGLINE)) {
            $fixed['content'] = self::REQUIRED_TAGLINE . ' ' . $fixed['content'];
        }

        // Fix 2: Remove duplicate words in hook
        $fixed['hook'] = $this->removeDuplicateWords($fixed['hook']);

        // Fix 3: Clean up typos
        $fixed['content'] = $this->fixCommonTypos($fixed['content']);

        // Fix 4: Ensure #IntinyaGini
        if (!str_contains($fixed['caption'], '#IntinyaGini')) {
            $fixed['caption'] .= ' #IntinyaGini';
        }

        // Fix 5: Trim whitespace
        $fixed['hook'] = trim($fixed['hook']);
        $fixed['content'] = trim($fixed['content']);
        $fixed['title'] = trim($fixed['title']);
        $fixed['caption'] = trim($fixed['caption']);

        return $fixed;
    }

    private function validateStructure(array $data): void
    {
        $required = ['hook', 'content', 'key_points', 'title', 'caption'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->errors[] = "Missing required field: {$field}";
            }
        }
    }

    private function validateHook(string $hook): void
    {
        if (strlen($hook) > self::MAX_HOOK_LENGTH) {
            $this->errors[] = "Hook too long (" . strlen($hook) . " chars, max " . self::MAX_HOOK_LENGTH . ")";
        }

        // Check for duplicate consecutive words
        if ($this->hasDuplicateWords($hook)) {
            $this->warnings[] = "Hook contains duplicate words: {$hook}";
        }

        // Check if hook is a question or has conflict
        if (!str_contains($hook, '?') && !str_contains($hook, '...')) {
            $this->warnings[] = "Hook should use '?' or '...' for engagement";
        }
    }

    private function validateContent(string $content): void
    {
        if (strlen($content) < self::MIN_CONTENT_LENGTH) {
            $this->errors[] = "Content too short (" . strlen($content) . " chars, min " . self::MIN_CONTENT_LENGTH . ")";
        }

        // Check for tagline
        if (!str_contains($content, 'Orang males baca')) {
            $this->errors[] = "Content missing brand tagline";
        }

        // Check for common typos
        $typos = [
            'juge-judge' => 'nge-judge',
            'ngerti' => 'ngerti',
            'ngeliat' => 'lihat/ngeliat',
        ];

        foreach ($typos as $bad => $good) {
            if (str_contains(strtolower($content), strtolower($bad))) {
                $this->warnings[] = "Possible typo: '{$bad}' (should be '{$good}'?)";
            }
        }
    }

    private function validateKeyPoints(array $points): void
    {
        if (count($points) < 3) {
            $this->errors[] = "Need at least 3 key points, got " . count($points);
        }

        foreach ($points as $i => $point) {
            if (strlen($point) > 100) {
                $this->warnings[] = "Key point " . ($i + 1) . " is too long (" . strlen($point) . " chars)";
            }
        }
    }

    private function validateCaption(string $caption): void
    {
        if (!str_contains($caption, '#IntinyaGini')) {
            $this->warnings[] = "Caption missing #IntinyaGini hashtag";
        }

        // Check if caption has at least 2 hashtags
        $hashtagCount = substr_count($caption, '#');
        if ($hashtagCount < 2) {
            $this->warnings[] = "Caption should have at least 2 hashtags (has {$hashtagCount})";
        }
    }

    private function hasDuplicateWords(string $text): bool
    {
        $words = explode(' ', $text);
        for ($i = 0; $i < count($words) - 1; $i++) {
            if (strtolower($words[$i]) === strtolower($words[$i + 1])) {
                return true;
            }
        }
        return false;
    }

    private function removeDuplicateWords(string $text): string
    {
        return preg_replace('/\b(\w+)\s+\1\b/i', '', $text);
    }

    private function fixCommonTypos(string $text): string
    {
        $fixes = [
            'juge-judge' => 'nge-judge',
            'ngeliat' => 'liat',
            '  ' => ' ', // double spaces
        ];

        foreach ($fixes as $bad => $good) {
            $text = str_ireplace($bad, $good, $text);
        }

        return $text;
    }
}
