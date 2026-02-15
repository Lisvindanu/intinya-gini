<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Script extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'hook',
        'content',
        'key_points',
        'title',
        'caption',
    ];

    protected $casts = [
        'key_points' => 'array',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
