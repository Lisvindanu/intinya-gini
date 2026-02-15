<?php

namespace App\Infrastructure\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIClient
{
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        // Use local Ollama instead of OpenRouter
        $this->baseUrl = config('services.ollama.base_url', 'http://127.0.0.1:11434');
        $this->model = config('services.ollama.model', 'gemma:2b');
    }

    public function generate(string $prompt): array
    {
        try {
            $response = Http::timeout(180)->post($this->baseUrl . '/api/generate', [
                'model' => $this->model,
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'temperature' => 0.7,
                    'num_predict' => 400,      // Cukup untuk full JSON output (~350 tokens)
                    'num_ctx' => 2048,          // Batasi context window (hemat RAM)
                    'num_thread' => 4,          // Match vCPU cores
                ],
            ]);

            if (!$response->successful()) {
                Log::error('Ollama API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception('AI generation failed: ' . $response->body());
            }

            $data = $response->json();

            return [
                'content' => $data['response'] ?? '',
                'model' => $data['model'] ?? $this->model,
                'tokens' => $data['eval_count'] ?? null,
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
