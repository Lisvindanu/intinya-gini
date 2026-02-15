<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Generation extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'model_used',
        'tokens_used',
        'response_time',
    ];

    protected $casts = [
        'tokens_used' => 'integer',
        'response_time' => 'float',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
