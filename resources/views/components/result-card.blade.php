@props(['script'])

<div class="bg-gray-900 rounded-lg border border-gray-800 p-6 hover:border-gray-700 transition-colors">
    <div class="flex justify-between items-start mb-4">
        <div class="flex-1">
            <h3 class="text-lg font-bold text-white mb-1">{{ $script->title }}</h3>
            <p class="text-sm text-gray-400">{{ $script->topic->title }} • {{ $script->topic->duration }}s</p>
        </div>
        <span class="text-xs text-gray-500">{{ $script->created_at->diffForHumans() }}</span>
    </div>

    <div class="space-y-3 text-sm text-gray-300">
        <div>
            <span class="font-semibold text-accent">Hook:</span>
            <p class="mt-1">{{ $script->hook }}</p>
        </div>

        <div>
            <span class="font-semibold text-accent">Script:</span>
            <p class="mt-1 line-clamp-3">{{ $script->content }}</p>
        </div>
    </div>

    <div class="mt-4 flex gap-2">
        <a
            href="{{ route('scripts.show', $script->id) }}"
            class="flex-1 px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-center rounded-lg transition-colors text-sm"
        >
            Lihat Detail
        </a>
    </div>
</div>
