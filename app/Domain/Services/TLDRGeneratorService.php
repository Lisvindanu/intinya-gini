<?php

namespace App\Domain\Services;

use App\Domain\Entities\Topic;
use App\Infrastructure\AI\AIClient;

class TLDRGeneratorService
{
    public function __construct(
        private AIClient $aiClient,
        private ScriptFormatter $formatter
    ) {}

    public function generate(Topic $topic): array
    {
        $startTime = microtime(true);

        $prompt = $this->buildPrompt($topic);
        $response = $this->aiClient->generate($prompt);

        $responseTime = (microtime(true) - $startTime) * 1000;

        $parsed = $this->formatter->parse($response['content']);

        return [
            'script_data' => $parsed,
            'metadata' => [
                'model_used' => $response['model'],
                'tokens_used' => $response['tokens'] ?? null,
                'response_time' => $responseTime,
            ],
        ];
    }

    private function buildPrompt(Topic $topic): string
    {
        return <<<PROMPT
Kamu adalah AI penulis script konten TL;DR untuk channel bernama "Intinya Gini".

Karakter channel:
- Santai
- Langsung ke inti
- Bahasa Indonesia kasual
- Insight padat tapi singkat

Hook wajib menggunakan gaya relatable.

WAJIB selalu memasukkan kalimat utama berikut di awal atau akhir:
"Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang."

Format output:
1. Hook (1 kalimat)
2. TL;DR Script (maks 120 kata)
3. 3 Poin Inti
4. Judul YouTube Shorts (clickable)
5. Caption singkat

Gaya bahasa:
- Tidak terlalu formal
- Tidak bertele-tele
- Fokus inti, bukan detail

Jika topik kompleks, sederhanakan tanpa kehilangan makna.

---

Topik: {$topic->title}
Durasi target: {$topic->duration} detik
Style: Santai tech bro
Channel: Intinya Gini

Buatkan TL;DR sesuai format di atas. Output dalam format JSON dengan struktur:
{
    "hook": "string",
    "content": "string",
    "key_points": ["string", "string", "string"],
    "title": "string",
    "caption": "string"
}
PROMPT;
    }
}
