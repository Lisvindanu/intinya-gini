@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('scripts.show', $script->id) }}" class="text-blue-400 hover:text-blue-300 mb-4 inline-block">
            ← Kembali ke Script
        </a>
        
        <h1 class="text-3xl font-bold text-white mb-2">
            Semua Variasi Script
        </h1>
        
        <p class="text-gray-400">
            Topik: <span class="text-white font-semibold">{{ $rootScript->topic->title }}</span>
        </p>
        
        <p class="text-gray-500 text-sm mt-1">
            Total {{ $allVersions->count() }} versi
        </p>
    </div>

    {{-- Version Timeline --}}
    <div class="mb-8 p-4 bg-gray-900/50 rounded-lg border border-gray-800">
        <div class="flex items-center gap-2 overflow-x-auto pb-2">
            @foreach($allVersions as $v)
                <a href="{{ route('scripts.show', $v->id) }}" 
                   class="px-4 py-2 rounded-lg border whitespace-nowrap transition-all
                          {{ $v->id === $script->id 
                             ? 'bg-blue-600 border-blue-500 text-white font-semibold' 
                             : 'bg-gray-800 border-gray-700 text-gray-300 hover:bg-gray-700' }}">
                    V{{ $v->version }}
                    @if($v->isOriginal())
                        <span class="text-xs opacity-75">Original</span>
                    @else
                        <span class="text-xs opacity-75">
                            {{ ucfirst(str_replace('_', ' ', $v->variation_type)) }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Comparison Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($allVersions as $version)
            <div class="bg-gray-900/50 rounded-lg border border-gray-800 p-6 hover:border-gray-700 transition-all">
                {{-- Version Header --}}
                <div class="flex items-start justify-between mb-4 pb-4 border-b border-gray-800">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-blue-600 text-white text-sm font-bold rounded-full">
                                Versi {{ $version->version }}
                            </span>
                            
                            @if($version->isOriginal())
                                <span class="px-2 py-1 bg-green-600 text-white text-xs rounded">
                                    Original
                                </span>
                            @endif
                            
                            @if($version->id === $script->id)
                                <span class="px-2 py-1 bg-yellow-600 text-white text-xs rounded">
                                    Saat Ini
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-gray-400 text-sm">
                            {{ $version->created_at->diffForHumans() }}
                        </p>
                        
                        @if($version->generation_config)
                            @php
                                $config = is_string($version->generation_config) 
                                    ? json_decode($version->generation_config, true) 
                                    : $version->generation_config;
                            @endphp
                            
                            @if(isset($config['prompt_name']))
                                <p class="text-xs text-gray-500 mt-1">
                                    Prompt: <span class="text-blue-400">{{ $config['prompt_name'] }}</span>
                                </p>
                            @endif
                        @endif
                    </div>
                    
                    <a href="{{ route('scripts.show', $version->id) }}" 
                       class="text-blue-400 hover:text-blue-300 text-sm">
                        Lihat Detail →
                    </a>
                </div>

                {{-- Hook --}}
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-400 mb-2">🎯 Hook</h3>
                    <p class="text-white bg-gray-800/50 p-3 rounded-lg border border-gray-700">
                        {{ $version->hook }}
                    </p>
                </div>

                {{-- Content Preview --}}
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-400 mb-2">📝 Script (Preview)</h3>
                    <p class="text-gray-300 text-sm bg-gray-800/50 p-3 rounded-lg border border-gray-700 line-clamp-4">
                        {{ Str::limit($version->content, 200) }}
                    </p>
                </div>

                {{-- Key Points --}}
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-400 mb-2">💡 Poin Inti</h3>
                    <ul class="space-y-2">
                        @php
                            $keyPoints = is_string($version->key_points) 
                                ? json_decode($version->key_points, true) 
                                : $version->key_points;
                        @endphp
                        
                        @foreach($keyPoints ?? [] as $point)
                            <li class="text-gray-300 text-sm flex items-start gap-2">
                                <span class="text-blue-400 mt-1">•</span>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Title --}}
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-400 mb-2">🎬 Judul YouTube</h3>
                    <p class="text-white text-sm bg-gray-800/50 p-3 rounded-lg border border-gray-700">
                        {{ $version->title }}
                    </p>
                </div>

                {{-- Caption Preview --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 mb-2">📱 Caption</h3>
                    <p class="text-gray-300 text-xs bg-gray-800/50 p-3 rounded-lg border border-gray-700 line-clamp-3">
                        {{ Str::limit($version->caption, 150) }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Action Buttons --}}
    <div class="mt-8 flex gap-4 justify-center">
        <a href="{{ route('scripts.show', $rootScript->id) }}" 
           class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition-colors border border-gray-700">
            Lihat Original
        </a>
        
        <a href="{{ route('dashboard') }}" 
           class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-colors">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
