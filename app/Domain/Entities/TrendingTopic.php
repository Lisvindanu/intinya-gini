<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrendingTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'title',
        'description',
        'url',
        'score',
        'upvotes',
        'comments',
        'category',
        'metadata',
        'is_used',
        'fetched_at',
    ];

    protected $casts = [
        'score' => 'integer',
        'upvotes' => 'integer',
        'comments' => 'integer',
        'metadata' => 'array',
        'is_used' => 'boolean',
        'fetched_at' => 'datetime',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(TopicSource::class, 'source_id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'trending_topic_id');
    }

    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }

    public function calculateScore(): int
    {
        $score = 0;

        if ($this->upvotes) {
            $score += $this->upvotes;
        }

        if ($this->comments) {
            $score += $this->comments * 2;
        }

        $hoursOld = $this->fetched_at->diffInHours(now());
        $agePenalty = max(0, 100 - ($hoursOld * 5));
        $score = $score * ($agePenalty / 100);

        return (int) $score;
    }
}
