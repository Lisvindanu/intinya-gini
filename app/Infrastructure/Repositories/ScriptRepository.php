<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Script;
use Illuminate\Database\Eloquent\Collection;

class ScriptRepository
{
    public function findById(int $id): ?Script
    {
        return Script::with('topic')->find($id);
    }

    public function getAll(int $limit = 50): Collection
    {
        return Script::with('topic')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getByTopicId(int $topicId): ?Script
    {
        return Script::where('topic_id', $topicId)->first();
    }

    public function delete(int $id): bool
    {
        $script = $this->findById($id);

        if (!$script) {
            return false;
        }

        return $script->delete();
    }
}
