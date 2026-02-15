@extends('layouts.app')

@section('title', $script->title . ' - Intinya Gini')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('scripts.index') }}" class="text-accent hover:text-green-400 text-sm">
            ← Kembali
        </a>
    </div>

    <div class="bg-gray-900 rounded-lg border border-gray-800 p-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-white mb-2">{{ $script->title }}</h1>
            <p class="text-gray-400">{{ $script->topic->title }} • {{ $script->topic->duration }} detik</p>
        </div>

        <div class="space-y-6">
            <div>
                <h3 class="text-sm font-semibold text-accent uppercase tracking-wide mb-2">Hook</h3>
                <div class="bg-gray-800 rounded-lg p-4">
                    <p class="text-gray-200">{{ $script->hook }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-accent uppercase tracking-wide mb-2">Script TL;DR</h3>
                <div class="bg-gray-800 rounded-lg p-4">
                    <p class="text-gray-200 leading-relaxed">{{ $script->content }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-accent uppercase tracking-wide mb-2">Poin Inti</h3>
                <div class="bg-gray-800 rounded-lg p-4">
                    <ol class="space-y-2">
                        @foreach($script->key_points as $index => $point)
                            <li class="text-gray-200">
                                <span class="font-bold text-accent">{{ $index + 1 }}.</span> {{ $point }}
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-accent uppercase tracking-wide mb-2">Caption</h3>
                <div class="bg-gray-800 rounded-lg p-4">
                    <p class="text-gray-200">{{ $script->caption }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-800" x-data="{ copied: false }">
            <h3 class="text-sm font-semibold text-accent uppercase tracking-wide mb-4">Export</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <button
                    @click="navigator.clipboard.writeText('{{ addslashes($script->content) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="px-4 py-2 bg-accent hover:bg-green-600 text-white rounded-lg transition-colors text-sm"
                >
                    <span x-show="!copied">Copy Script</span>
                    <span x-show="copied">Copied!</span>
                </button>

                <a
                    href="{{ route('scripts.export', [$script->id, 'text']) }}"
                    class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-center rounded-lg transition-colors text-sm"
                >
                    Download TXT
                </a>

                <a
                    href="{{ route('scripts.export', [$script->id, 'markdown']) }}"
                    class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-center rounded-lg transition-colors text-sm"
                >
                    Download MD
                </a>

                <a
                    href="{{ route('scripts.export', [$script->id, 'srt']) }}"
                    class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-center rounded-lg transition-colors text-sm"
                >
                    Download SRT
                </a>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-800">
            <form action="{{ route('scripts.destroy', $script->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus script ini?')">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-900/50 hover:bg-red-900 text-red-200 rounded-lg transition-colors text-sm"
                >
                    Hapus Script
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
