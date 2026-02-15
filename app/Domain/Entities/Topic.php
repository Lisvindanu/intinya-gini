<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'duration',
        'trending_topic_id',
        'source_type',
    ];

    protected $casts = [
        'duration' => 'integer',
    ];

    public function script(): HasOne
    {
        return $this->hasOne(Script::class);
    }

    public function generations(): HasMany
    {
        return $this->hasMany(Generation::class);
    }

    public function trendingTopic(): BelongsTo
    {
        return $this->belongsTo(TrendingTopic::class, 'trending_topic_id');
    }

    public function isFromTrending(): bool
    {
        return $this->trending_topic_id !== null;
    }
}
