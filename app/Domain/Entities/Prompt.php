<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    protected $fillable = [
        'name',
        'description',
        'system_prompt',
        'user_prompt_template',
        'config',
        'is_active',
        'version',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getActive(string $name): ?self
    {
        return self::where('name', $name)
            ->where('is_active', true)
            ->latest('version')
            ->first();
    }

    public function render(array $variables): string
    {
        $template = $this->user_prompt_template;
        
        foreach ($variables as $key => $value) {
            $template = str_replace("{{{$key}}}", $value, $template);
        }
        
        return $template;
    }
}
