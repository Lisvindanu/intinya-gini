<div class="bg-gray-900 rounded-lg border border-gray-800 p-6">
    <h2 class="text-xl font-bold text-white mb-4">Buat TL;DR Baru</h2>

    <form action="{{ route('scripts.generate') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="topic" class="block text-sm font-medium text-gray-300 mb-2">
                Topik
            </label>
            <textarea
                name="topic"
                id="topic"
                rows="3"
                class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                placeholder="Contoh: Kotlin Coroutines"
                required
            >{{ old('topic') }}</textarea>
            @error('topic')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="duration" class="block text-sm font-medium text-gray-300 mb-2">
                Durasi Target
            </label>
            <select
                name="duration"
                id="duration"
                class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                required
            >
                <option value="30" {{ old('duration') == 30 ? 'selected' : '' }}>30 detik</option>
                <option value="60" {{ old('duration', 60) == 60 ? 'selected' : '' }}>60 detik</option>
            </select>
            @error('duration')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full px-6 py-3 bg-accent hover:bg-green-600 text-white font-semibold rounded-lg transition-colors duration-200"
        >
            Generate TL;DR
        </button>
    </form>
</div>
