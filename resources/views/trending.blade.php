@extends('layouts.app')

@section('title', 'Trending Topics - Intinya Gini')

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2">Trending Topics</h1>
        <p class="text-gray-400">Topik trending dari berbagai sumber untuk konten TL;DR kamu</p>
    </div>
    
    <form action="{{ route('trending.fetch') }}" method="POST" onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').textContent = 'Fetching...'; return true;">
        @csrf
        <button
            type="submit"
            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors whitespace-nowrap"
        >
            🔄 Fetch Trending Topics
        </button>
    </form>
</div>

@if($trending->isEmpty())
    <div class="bg-gray-900 rounded-lg border border-gray-800 p-12 text-center">
        <p class="text-gray-400 mb-4">Belum ada trending topics yang di-fetch.</p>
        <p class="text-sm text-gray-500 mb-6">Klik tombol "Fetch Trending Topics" di atas untuk mengambil berita terbaru</p>
    </div>
@else
    <div class="grid grid-cols-1 gap-4">
        @foreach($trending as $topic)
            <div class="bg-gray-900 rounded-lg border border-gray-800 p-6 hover:border-gray-700 transition-colors" x-data="{ expanded: false }">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start gap-3 mb-2">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-white mb-1 line-clamp-2">
                                    {{ $topic->title }}
                                </h3>
                                <div class="flex items-center gap-3 text-sm text-gray-400">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-800 text-gray-300">
                                        {{ $topic->source->name }}
                                    </span>
                                    @if($topic->category)
                                        <span class="text-gray-500">{{ $topic->category }}</span>
                                    @endif
                                    <span class="text-gray-500">{{ $topic->fetched_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        @if($topic->description)
                            <div x-show="expanded" x-collapse class="mt-3">
                                <p class="text-gray-300 text-sm">{{ Str::limit($topic->description, 300) }}</p>
                            </div>
                        @endif

                        <div class="flex items-center gap-4 mt-3 text-sm">
                            <div class="flex items-center gap-4 text-gray-500">
                                @if($topic->upvotes)
                                    <span>⬆ {{ number_format($topic->upvotes) }}</span>
                                @endif
                                @if($topic->comments)
                                    <span>💬 {{ number_format($topic->comments) }}</span>
                                @endif
                                <span class="text-accent font-semibold">Score: {{ $topic->score }}</span>
                            </div>

                            @if($topic->description)
                                <button
                                    @click="expanded = !expanded"
                                    class="text-gray-400 hover:text-white text-xs"
                                >
                                    <span x-show="!expanded">Lihat Detail</span>
                                    <span x-show="expanded">Sembunyikan</span>
                                </button>
                            @endif

                            @if($topic->url)
                                <a
                                    href="{{ $topic->url }}"
                                    target="_blank"
                                    class="text-accent hover:text-green-400 text-xs"
                                >
                                    Buka Link ↗
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        @if($topic->is_used)
                            <span class="px-3 py-1 bg-gray-800 text-gray-500 text-xs rounded-lg text-center">
                                Sudah Digunakan
                            </span>
                        @else
                            <form action="{{ route('trending.generate', $topic->id) }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-accent hover:bg-green-600 text-white text-sm rounded-lg transition-colors whitespace-nowrap"
                                >
                                    Generate TL;DR
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
