<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('scripts.show', $script->id) }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Semua Variasi Script
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $rootScript->topic->title }}
                    </p>
                </div>
            </div>
            
            <span class="px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded-lg shadow-sm">
                {{ $allVersions->count() }} Versi
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Version Timeline --}}
            <div class="bg-white rounded-xl p-4 shadow-md border border-gray-200">
                <div class="flex items-center gap-2 overflow-x-auto pb-2">
                    @foreach($allVersions as $v)
                        <a href="{{ route('scripts.show', $v->id) }}" 
                           class="px-4 py-2 rounded-lg border whitespace-nowrap transition-all flex items-center gap-2
                                  {{ $v->id === $script->id 
                                     ? 'bg-blue-600 border-blue-600 text-white font-semibold shadow-md' 
                                     : 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200' }}">
                            <span class="font-bold">V{{ $v->version }}</span>
                            @if($v->isOriginal())
                                <span class="text-xs px-2 py-0.5 {{ $v->id === $script->id ? 'bg-green-500' : 'bg-green-100 text-green-700' }} rounded">
                                    Original
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Comparison Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($allVersions as $version)
                    <div class="bg-white rounded-xl shadow-md border-2 transition-all overflow-hidden
                                {{ $version->id === $script->id ? 'border-blue-500 shadow-lg' : 'border-gray-200 hover:border-blue-300' }}">
                        
                        {{-- Version Header --}}
                        <div class="p-5 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-blue-600 text-white text-sm font-bold rounded-full">
                                        Versi {{ $version->version }}
                                    </span>
                                    
                                    @if($version->isOriginal())
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full border border-green-200">
                                            Original
                                        </span>
                                    @endif
                                    
                                    @if($version->id === $script->id)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full border border-yellow-200">
                                            Viewing
                                        </span>
                                    @endif
                                </div>
                                
                                <a href="{{ route('scripts.show', $version->id) }}" 
                                   class="text-blue-600 hover:text-blue-700 text-sm font-semibold flex items-center gap-1">
                                    Detail
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            
                            <p class="text-gray-600 text-sm">
                                📅 {{ $version->created_at->diffForHumans() }}
                            </p>
                            
                            @if($version->generation_config)
                                @php
                                    $config = is_string($version->generation_config) 
                                        ? json_decode($version->generation_config, true) 
                                        : $version->generation_config;
                                @endphp
                                
                                @if(isset($config['prompt_name']))
                                    <div class="mt-2 inline-flex items-center gap-1 px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded border border-purple-200">
                                        🎯 {{ $config['prompt_name'] }}
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Content Preview --}}
                        <div class="p-5 space-y-4">
                            {{-- Hook --}}
                            <div>
                                <h3 class="text-xs font-bold text-gray-700 mb-2">🎯 HOOK</h3>
                                <p class="text-gray-900 font-semibold bg-blue-50 p-3 rounded-lg border border-blue-100">
                                    {{ $version->hook }}
                                </p>
                            </div>

                            {{-- Content Preview --}}
                            <div>
                                <h3 class="text-xs font-bold text-gray-700 mb-2">📝 SCRIPT</h3>
                                <p class="text-gray-700 text-sm bg-gray-50 p-3 rounded-lg border border-gray-200 line-clamp-3">
                                    {{ Str::limit($version->content, 200) }}
                                </p>
                            </div>

                            {{-- Key Points --}}
                            <div>
                                <h3 class="text-xs font-bold text-gray-700 mb-2">💡 POIN INTI</h3>
                                <ul class="space-y-1">
                                    @php
                                        $keyPoints = is_string($version->key_points) 
                                            ? json_decode($version->key_points, true) 
                                            : $version->key_points;
                                    @endphp
                                    
                                    @foreach($keyPoints ?? [] as $point)
                                        <li class="text-gray-700 text-xs flex items-start gap-2">
                                            <span class="text-blue-600 font-bold">•</span>
                                            <span class="flex-1">{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Title --}}
                            <div>
                                <h3 class="text-xs font-bold text-gray-700 mb-2">🎬 JUDUL</h3>
                                <p class="text-gray-900 text-sm font-semibold bg-gray-50 p-3 rounded-lg border border-gray-200">
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
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-colors border border-gray-300">
                    Lihat Original
                </a>
                
                <a href="{{ route('dashboard') }}" 
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
