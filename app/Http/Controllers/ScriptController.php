<?php

namespace App\Http\Controllers;

use App\Application\Actions\ExportScriptAction;
use App\Application\Actions\GenerateScriptAction;
use App\Application\Actions\RegenerateScriptAction;
use App\Domain\Entities\Script;
use App\Http\Requests\GenerateScriptRequest;
use App\Infrastructure\Repositories\ScriptRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScriptController extends Controller
{
    public function __construct(
        private GenerateScriptAction $generateAction,
        private RegenerateScriptAction $regenerateAction,
        private ExportScriptAction $exportAction,
        private ScriptRepository $repository
    ) {}

    public function index(): View
    {
        // Only show original scripts on dashboard
        $scripts = Script::with('topic')
            ->originals()
            ->latest()
            ->paginate(20);

        return view('dashboard', [
            'scripts' => $scripts,
        ]);
    }

    public function generate(GenerateScriptRequest $request): RedirectResponse
    {
        try {
            $result = $this->generateAction->execute(
                topicTitle: $request->input('topic'),
                duration: $request->input('duration')
            );

            return redirect()
                ->route('scripts.show', $result->scriptId)
                ->with('success', 'Script TL;DR berhasil dibuat!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat script: ' . $e->getMessage());
        }
    }

    public function regenerate(int $id, Request $request): RedirectResponse
    {
        try {
            $promptName = $request->input('prompt_name');
            
            $result = $this->regenerateAction->execute($id, $promptName);

            return redirect()
                ->route('scripts.show', $result->scriptId)
                ->with('success', "Variasi baru berhasil dibuat! (Versi {$result->metadata['version']})");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal regenerate: ' . $e->getMessage());
        }
    }

    public function variations(int $id): View
    {
        $script = Script::with(['topic', 'parent', 'variations'])->findOrFail($id);
        
        // Get root script and all its variations
        $rootId = $script->getRootScriptId();
        $rootScript = Script::with('variations')->find($rootId);
        
        // Collect all versions
        $allVersions = collect([$rootScript])->merge($rootScript->variations);

        return view('script-variations', [
            'script' => $script,
            'rootScript' => $rootScript,
            'allVersions' => $allVersions,
        ]);
    }

    public function show(int $id): View
    {
        $script = Script::with(['topic', 'variations', 'parent'])->findOrFail($id);

        if (!$script) {
            abort(404, 'Script tidak ditemukan');
        }

        return view('script-detail', [
            'script' => $script,
        ]);
    }

    public function export(int $id, string $format = 'text')
    {
        try {
            $content = $this->exportAction->execute($id, $format);
            $filename = $this->exportAction->getFilename($id, $format);

            $mimeType = match ($format) {
                'srt' => 'text/plain',
                'markdown' => 'text/markdown',
                default => 'text/plain',
            };

            return response($content)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->repository->delete($id);

            return redirect()
                ->route('scripts.index')
                ->with('success', 'Script berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
