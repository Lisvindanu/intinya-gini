<?php

namespace App\Application\DTO;

class ScriptResultDTO
{
    public function __construct(
        public readonly int $topicId,
        public readonly int $scriptId,
        public readonly string $hook,
        public readonly string $content,
        public readonly array $keyPoints,
        public readonly string $title,
        public readonly string $caption,
        public readonly array $metadata
    ) {}

    public function toArray(): array
    {
        return [
            'topic_id' => $this->topicId,
            'script_id' => $this->scriptId,
            'hook' => $this->hook,
            'content' => $this->content,
            'key_points' => $this->keyPoints,
            'title' => $this->title,
            'caption' => $this->caption,
            'metadata' => $this->metadata,
        ];
    }
}
