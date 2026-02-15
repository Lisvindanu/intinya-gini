<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('scripts.show', $script->id) }}" class="text-gray-400 hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                        Semua Variasi Script
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $rootScript->topic->title }}
                    </p>
                </div>
            </div>
            
            <span class="px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded-lg">
                {{ $allVersions->count() }} Versi
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Version Timeline --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2 overflow-x-auto pb-2">
                    @foreach($allVersions as $v)
                        <a href="{{ route('scripts.show', $v->id) }}" 
                           class="px-4 py-2 rounded-lg border whitespace-nowrap transition-all flex items-center gap-2
                                  {{ $v->id === $script->id 
                                     ? 'bg-blue-600 border-blue-500 text-white font-semibold shadow-lg' 
                                     : 'bg-gray-700 border-gray-600 text-gray-300 hover:bg-gray-600' }}">
                            <span class="font-bold">V{{ $v->version }}</span>
                            @if($v->isOriginal())
                                <span class="text-xs px-2 py-0.5 bg-green-500 rounded">Original</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Comparison Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($allVersions as $version)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-all overflow-hidden
                                {{ $version->id === $script->id ? 'ring-2 ring-blue-500' : '' }}">
                        
                        {{-- Version Header --}}
                        <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-blue-600 text-white text-sm font-bold rounded-full">
                                        Versi {{ $version->version }}
                                    </span>
                                    
                                    @if($version->isOriginal())
                                        <span class="px-2 py-1 bg-green-600 text-white text-xs rounded-full">
                                            Original
                                        </span>
                                    @endif
                                    
                                    @if($version->id === $script->id)
                                        <span class="px-2 py-1 bg-yellow-600 text-white text-xs rounded-full">
                                            Viewing
                                        </span>
                                    @endif
                                </div>
                                
                                <a href="{{ route('scripts.show', $version->id) }}" 
                                   class="text-blue-400 hover:text-blue-300 text-sm font-semibold flex items-center gap-1">
                                    Detail
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                📅 {{ $version->created_at->diffForHumans() }}
                            </p>
                            
                            @if($version->generation_config)
                                @php
                                    $config = is_string($version->generation_config) 
                                        ? json_decode($version->generation_config, true) 
                                        : $version->generation_config;
                                @endphp
                                
                                @if(isset($config['prompt_name']))
                                    <div class="mt-2 inline-flex items-center gap-1 px-2 py-1 bg-purple-600 text-white text-xs rounded">
                                        🎯 {{ $config['prompt_name'] }}
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Content Preview --}}
                        <div class="p-5 space-y-4">
                            {{-- Hook --}}
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">🎯 HOOK</h3>
                                <p class="text-gray-900 dark:text-gray-100 font-semibold bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-200 dark:border-blue-800">
                                    {{ $version->hook }}
                                </p>
                            </div>

                            {{-- Content Preview --}}
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">📝 SCRIPT</h3>
                                <p class="text-gray-700 dark:text-gray-300 text-sm bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700 line-clamp-3">
                                    {{ Str::limit($version->content, 200) }}
                                </p>
                            </div>

                            {{-- Key Points --}}
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">💡 POIN INTI</h3>
                                <ul class="space-y-1">
                                    @php
                                        $keyPoints = is_string($version->key_points) 
                                            ? json_decode($version->key_points, true) 
                                            : $version->key_points;
                                    @endphp
                                    
                                    @foreach($keyPoints ?? [] as $point)
                                        <li class="text-gray-700 dark:text-gray-300 text-xs flex items-start gap-2">
                                            <span class="text-blue-500 font-bold">•</span>
                                            <span class="flex-1">{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Title --}}
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">🎬 JUDUL</h3>
                                <p class="text-gray-900 dark:text-gray-100 text-sm font-semibold bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                                    {{ $version->title }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-4 justify-center">
                <a href="{{ route('scripts.show', $rootScript->id) }}" 
                   class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors font-semibold">
                    Lihat Original
                </a>
                
                <a href="{{ route('dashboard') }}" 
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-semibold">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
