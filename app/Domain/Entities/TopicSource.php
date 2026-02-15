<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopicSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'fetch_interval',
        'config',
        'last_fetched_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fetch_interval' => 'integer',
        'config' => 'array',
        'last_fetched_at' => 'datetime',
    ];

    public function trendingTopics(): HasMany
    {
        return $this->hasMany(TrendingTopic::class, 'source_id');
    }

    public function shouldFetch(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->last_fetched_at) {
            return true;
        }

        return $this->last_fetched_at->addSeconds($this->fetch_interval)->isPast();
    }
}
