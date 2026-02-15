<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateScriptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'topic' => ['required', 'string', 'min:3', 'max:500'],
            'duration' => ['required', 'integer', 'in:30,60'],
        ];
    }

    public function messages(): array
    {
        return [
            'topic.required' => 'Topik wajib diisi.',
            'topic.min' => 'Topik minimal 3 karakter.',
            'topic.max' => 'Topik maksimal 500 karakter.',
            'duration.required' => 'Durasi wajib dipilih.',
            'duration.in' => 'Durasi harus 30 atau 60 detik.',
        ];
    }
}
