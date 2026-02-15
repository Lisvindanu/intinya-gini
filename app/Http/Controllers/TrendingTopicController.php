<?php

namespace App\Http\Controllers;

use App\Application\Actions\GenerateScriptAction;
use App\Domain\Entities\TopicSource;
use App\Domain\Services\TrendingTopicService;
use App\Infrastructure\Scrapers\GoogleNewsIndonesiaScraper;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrendingTopicController extends Controller
{
    public function __construct(
        private TrendingTopicService $trendingService,
        private GenerateScriptAction $generateAction
    ) {}

    public function index(): View
    {
        $trending = $this->trendingService->getTopTrending(50);

        return view('trending', [
            'trending' => $trending,
        ]);
    }

    public function fetch(): RedirectResponse
    {
        try {
            $sources = TopicSource::where('is_active', true)->get();

            if ($sources->isEmpty()) {
                return redirect()
                    ->route('trending.index')
                    ->with('error', 'Tidak ada sumber aktif. Jalankan: php artisan sources:seed');
            }

            $totalFetched = 0;

            foreach ($sources as $source) {
                $scraper = $this->getScraperForSource($source);

                if (!$scraper) {
                    continue;
                }

                $count = $this->trendingService->fetchFromSource($source, $scraper);
                $totalFetched += $count;
            }

            // Recalculate scores
            $this->trendingService->recalculateScores();

            // Clean old topics (older than 7 days)
            $deleted = $this->trendingService->cleanOldTopics(7);

            $message = "Berhasil fetch {$totalFetched} trending topics.";
            if ($deleted > 0) {
                $message .= " Dihapus {$deleted} topik lama.";
            }

            return redirect()
                ->route('trending.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()
                ->route('trending.index')
                ->with('error', 'Gagal fetch trending: ' . $e->getMessage());
        }
    }

    private function getScraperForSource(TopicSource $source)
    {
        return match ($source->type) {
            'google_news' => new GoogleNewsIndonesiaScraper(
                $source->config['category'] ?? 'NATION',
                $source->config['limit'] ?? 20
            ),
            default => null,
        };
    }

    public function generate(int $id): RedirectResponse
    {
        try {
            $trending = \App\Domain\Entities\TrendingTopic::findOrFail($id);

            $result = $this->generateAction->execute(
                topicTitle: $trending->title,
                duration: 60
            );

            $topic = \App\Domain\Entities\Topic::find($result->topicId);
            $topic->update([
                'trending_topic_id' => $trending->id,
                'source_type' => $trending->source->type,
            ]);

            $trending->markAsUsed();

            return redirect()
                ->route('scripts.show', $result->scriptId)
                ->with('success', 'Script TL;DR dari trending topic berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat script: ' . $e->getMessage());
        }
    }
}
