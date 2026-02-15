<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Script Detail
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $script->topic->title }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-blue-600 text-white text-sm font-bold rounded-full">
                    Versi {{ $script->version }}
                </span>
                @if($script->variation_type !== 'original')
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm rounded-full border border-purple-200">
                        {{ ucfirst(str_replace('_', ' ', $script->variation_type)) }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Variations Alert --}}
            @if($script->hasVariations())
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-5">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-purple-800 font-semibold mb-2">
                                Script ini punya {{ $script->variations->count() }} variasi lainnya
                            </p>
                            <a href="{{ route('scripts.variations', $script->id) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                                Lihat {{ $script->variations->count() }} Variasi
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Main Content Card (30% - White surface) --}}
            <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">
                
                {{-- Hook Section --}}
                <div class="border-b border-gray-200 p-6 bg-blue-50">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="text-2xl">🎯</span>
                        HOOK
                    </h3>
                    <p class="text-xl font-bold text-gray-900 leading-relaxed">
                        {{ $script->hook }}
                    </p>
                </div>

                {{-- Script Content --}}
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="text-2xl">📝</span>
                        SCRIPT
                    </h3>
                    <div class="prose max-w-none">
                        <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ $script->content }}</p>
                    </div>
                </div>

                {{-- Key Points --}}
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="text-2xl">💡</span>
                        POIN INTI
                    </h3>
                    <ul class="space-y-3">
                        @php
                            $keyPoints = is_string($script->key_points) 
                                ? json_decode($script->key_points, true) 
                                : $script->key_points;
                        @endphp
                        
                        @foreach($keyPoints ?? [] as $point)
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    {{ $loop->iteration }}
                                </span>
                                <span class="text-gray-800 leading-relaxed">{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- YouTube Title --}}
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="text-2xl">🎬</span>
                        JUDUL YOUTUBE SHORTS
                    </h3>
                    <p class="text-lg font-bold text-gray-900">
                        {{ $script->title }}
                    </p>
                </div>

                {{-- Caption --}}
                <div class="p-6">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="text-2xl">📱</span>
                        CAPTION
                    </h3>
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $script->caption }}</p>
                </div>
            </div>

            {{-- Regenerate Section (10% - Accent) --}}
            <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-purple-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Generate Variasi Baru
                    </h3>
                    <p class="text-purple-100 text-sm mt-1">Coba prompt yang berbeda untuk hasil yang bervariasi</p>
                </div>
                
                <div class="p-6 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Default Regenerate --}}
                        <form action="{{ route('scripts.regenerate', $script->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="prompt_name" value="tldr_v1">
                            <button type="submit" class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all shadow-sm hover:shadow-md flex flex-col items-center gap-2">
                                <span class="text-2xl">🔄</span>
                                <span class="text-sm">Regenerate</span>
                                <span class="text-xs opacity-90">(Default)</span>
                            </button>
                        </form>
                        
                        {{-- Drama Variation --}}
                        <form action="{{ route('scripts.regenerate', $script->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="prompt_name" value="tldr_drama">
                            <button type="submit" class="w-full px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-all shadow-sm hover:shadow-md flex flex-col items-center gap-2">
                                <span class="text-2xl">🎭</span>
                                <span class="text-sm">Versi Drama</span>
                                <span class="text-xs opacity-90">(Extra Neutral)</span>
                            </button>
                        </form>
                        
                        {{-- Tech Variation --}}
                        <form action="{{ route('scripts.regenerate', $script->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="prompt_name" value="tldr_tech">
                            <button type="submit" class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all shadow-sm hover:shadow-md flex flex-col items-center gap-2">
                                <span class="text-2xl">💻</span>
                                <span class="text-sm">Versi Tech</span>
                                <span class="text-xs opacity-90">(Explain)</span>
                            </button>
                        </form>
                    </div>
                    
                    <p class="text-xs text-gray-600 mt-4 text-center">
                        💡 Setiap variasi akan disimpan dan bisa lu bandingkan nanti
                    </p>
                </div>
            </div>

            {{-- Export & Actions --}}
            <div class="flex gap-3">
                <a href="{{ route('scripts.export', [$script->id, 'txt']) }}" 
                   class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-colors text-center border border-gray-300">
                    📄 Export TXT
                </a>
                <a href="{{ route('scripts.export', [$script->id, 'json']) }}" 
                   class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-colors text-center border border-gray-300">
                    📊 Export JSON
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
