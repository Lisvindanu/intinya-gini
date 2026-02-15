<?php

namespace App\Http\Controllers;

use App\Application\Actions\GenerateScriptAction;
use App\Domain\Services\TrendingTopicService;
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
