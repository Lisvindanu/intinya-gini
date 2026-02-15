<?php

namespace App\Http\Controllers;

use App\Application\Actions\ExportScriptAction;
use App\Application\Actions\GenerateScriptAction;
use App\Http\Requests\GenerateScriptRequest;
use App\Infrastructure\Repositories\ScriptRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ScriptController extends Controller
{
    public function __construct(
        private GenerateScriptAction $generateAction,
        private ExportScriptAction $exportAction,
        private ScriptRepository $repository
    ) {}

    public function index(): View
    {
        $scripts = $this->repository->getAll(20);

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

    public function show(int $id): View
    {
        $script = $this->repository->findById($id);

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
