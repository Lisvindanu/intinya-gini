<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                        🔥 Trending Topics
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Topik trending dari Google News Indonesia
                    </p>
                </div>
            </div>
            
            <form action="{{ route('trending.fetch') }}" method="POST">
                @csrf
                <button type="submit"
                   class="px-5 py-2.5 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white font-semibold rounded-lg transition-all transform hover:scale-105 active:scale-95 shadow-lg flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Fetch Trending
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-900/20 border border-green-700 text-green-200 px-6 py-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($trending->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-16 text-center shadow-lg">
                    <svg class="mx-auto h-20 w-20 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Belum ada trending topics</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Klik tombol "Fetch Trending" di atas untuk mengambil berita terbaru dari Google News Indonesia
                    </p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-lg">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($trending as $topic)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-3">
                                            @if($topic->is_used)
                                                <span class="px-3 py-1 bg-green-600 text-white text-xs font-bold rounded-full">
                                                    ✓ Sudah Dipakai
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-orange-600 text-white text-xs font-bold rounded-full">
                                                    🔥 Trending
                                                </span>
                                            @endif
                                            
                                            @if($topic->source_type)
                                                <span class="px-2 py-1 bg-blue-600/20 text-blue-400 text-xs rounded">
                                                    {{ $topic->source_type }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">
                                            {{ $topic->title }}
                                        </h4>
                                        
                                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                            <span>📅 {{ $topic->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-shrink-0">
                                        @if($topic->is_used)
                                            <span class="px-4 py-2 bg-gray-600 text-gray-300 rounded-lg text-sm font-semibold cursor-not-allowed">
                                                Sudah Dipakai
                                            </span>
                                        @else
                                            <form action="{{ route('trending.generate', $topic->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                   class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all transform hover:scale-105 active:scale-95 shadow-md whitespace-nowrap flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                    Generate Script
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($trending->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            {{ $trending->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
