<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Script extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'parent_script_id',
        'version',
        'variation_type',
        'generation_config',
        'hook',
        'content',
        'key_points',
        'title',
        'caption',
    ];

    protected $casts = [
        'key_points' => 'array',
        'generation_config' => 'array',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Script::class, 'parent_script_id');
    }

    public function variations(): HasMany
    {
        return $this->hasMany(Script::class, 'parent_script_id')->orderBy('version');
    }

    public function allVersions(): HasMany
    {
        // Get all versions including this one
        return $this->hasMany(Script::class, 'parent_script_id')
            ->orWhere('id', $this->getRootScriptId())
            ->orderBy('version');
    }

    public function getRootScriptId(): int
    {
        return $this->parent_script_id ?? $this->id;
    }

    public function getNextVersion(): int
    {
        if ($this->parent_script_id) {
            // This is already a variation, get next version from parent
            $maxVersion = $this->parent->variations()->max('version');
            return ($maxVersion ?? $this->parent->version) + 1;
        }
        
        // This is the original, get next version from variations
        $maxVersion = $this->variations()->max('version');
        return ($maxVersion ?? $this->version) + 1;
    }

    public function isOriginal(): bool
    {
        return $this->variation_type === 'original' && !$this->parent_script_id;
    }

    public function hasVariations(): bool
    {
        return $this->variations()->count() > 0;
    }

    public function scopeOriginals($query)
    {
        return $query->whereNull('parent_script_id');
    }

    public function scopeVariationsOf($query, int $scriptId)
    {
        return $query->where('parent_script_id', $scriptId)->orderBy('version');
    }
}
