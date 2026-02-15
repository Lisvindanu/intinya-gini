<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'duration',
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
}
