<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    📝 Intinya Gini - AI Content Factory
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Generate TL;DR scripts for YouTube Shorts
                </p>
            </div>
            <a href="{{ route('trending.index') }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-semibold">
                🔥 Trending Topics
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="bg-green-900/20 border border-green-700 text-green-200 px-6 py-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-900/20 border border-red-700 text-red-200 px-6 py-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Generate New Script Card --}}
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Generate Script Baru
                    </h3>
                    <p class="text-blue-100 text-sm mt-1">Masukkan topik yang ingin lu jadiin TL;DR</p>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('scripts.generate') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Topik / Judul
                            </label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                required
                                placeholder="Contoh: GPT-5 Bakal Rilis Tahun Ini, Apa Bedanya?"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all"
                            >
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Durasi Target (detik)
                                </label>
                                <input 
                                    type="number" 
                                    name="duration" 
                                    id="duration" 
                                    value="60"
                                    min="30"
                                    max="120"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100"
                                >
                            </div>
                            
                            <div>
                                <label for="prompt_style" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Style Konten
                                </label>
                                <select 
                                    name="prompt_style" 
                                    id="prompt_style"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100"
                                >
                                    <option value="">Auto-detect</option>
                                    <option value="tldr_v1">General / Default</option>
                                    <option value="tldr_drama">Drama / Gossip (Extra Neutral)</option>
                                    <option value="tldr_tech">Tech / Explanation</option>
                                </select>
                            </div>
                        </div>
                        
                        <button 
                            type="submit"
                            class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Generate Script
                        </button>
                    </form>
                </div>
            </div>

            {{-- Scripts List --}}
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                        📚 Riwayat Script (Original Only)
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Total: {{ $scripts->total() }} script
                    </p>
                </div>
                
                @if($scripts->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($scripts as $script)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-2 py-1 bg-blue-600 text-white text-xs font-bold rounded">
                                                V{{ $script->version }}
                                            </span>
                                            @if($script->hasVariations())
                                                <span class="px-2 py-1 bg-purple-600 text-white text-xs font-semibold rounded">
                                                    +{{ $script->variations->count() }} Variasi
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 truncate">
                                            {{ $script->topic->title }}
                                        </h4>
                                        
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                            🎯 {{ $script->hook }}
                                        </p>
                                        
                                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-500">
                                            <span>⏱️ {{ $script->topic->duration }}s</span>
                                            <span>📅 {{ $script->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col gap-2">
                                        <a href="{{ route('scripts.show', $script->id) }}" 
                                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors text-center whitespace-nowrap">
                                            Lihat Detail
                                        </a>
                                        
                                        @if($script->hasVariations())
                                            <a href="{{ route('scripts.variations', $script->id) }}" 
                                               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-colors text-center whitespace-nowrap">
                                                Lihat Variasi
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $scripts->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Belum ada script</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Mulai generate script pertama lu di atas!
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
