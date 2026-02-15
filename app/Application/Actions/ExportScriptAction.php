<?php

namespace App\Application\Actions;

use App\Domain\Entities\Script;
use App\Domain\Services\ScriptFormatter;

class ExportScriptAction
{
    public function __construct(
        private ScriptFormatter $formatter
    ) {}

    public function execute(int $scriptId, string $format = 'text'): string
    {
        $script = Script::findOrFail($scriptId);

        $scriptData = [
            'hook' => $script->hook,
            'content' => $script->content,
            'key_points' => $script->key_points,
            'title' => $script->title,
            'caption' => $script->caption,
        ];

        return $this->formatter->formatForExport($scriptData, $format);
    }

    public function getFilename(int $scriptId, string $format): string
    {
        $script = Script::findOrFail($scriptId);
        $slug = \Illuminate\Support\Str::slug($script->title);
        $extension = match ($format) {
            'srt' => 'srt',
            'markdown' => 'md',
            default => 'txt',
        };

        return "{$slug}.{$extension}";
    }
}
