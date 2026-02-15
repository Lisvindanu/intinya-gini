<?php

namespace App\Infrastructure\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIClient
{
    private string $apiKey;
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key');
        $this->baseUrl = config('services.openrouter.base_url', 'https://openrouter.ai/api/v1');
        $this->model = config('services.openrouter.model', 'meta-llama/llama-3.1-8b-instruct:free');
    }

    public function generate(string $prompt): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => 'Intinya Gini - AI TL;DR Factory',
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            if (!$response->successful()) {
                Log::error('OpenRouter API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception('AI generation failed: ' . $response->body());
            }

            $data = $response->json();

            return [
                'content' => $data['choices'][0]['message']['content'] ?? '',
                'model' => $data['model'] ?? $this->model,
                'tokens' => $data['usage']['total_tokens'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('AI Client Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function testConnection(): bool
    {
        try {
            $response = $this->generate('Test connection. Reply with OK.');
            return !empty($response['content']);
        } catch (\Exception $e) {
            return false;
        }
    }
}
