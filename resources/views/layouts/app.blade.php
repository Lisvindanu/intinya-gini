<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Intinya Gini - AI TL;DR Factory')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-950 text-gray-100">
    <div class="min-h-full">
        <nav class="bg-gray-900 border-b border-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('scripts.index') }}" class="flex items-center">
                            <span class="text-2xl font-bold text-white">IG</span>
                            <span class="ml-2 text-sm text-gray-400">intinya gini</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-500">AI TL;DR Factory</span>
                    </div>
                </div>
            </div>
        </nav>

        @if(session('success'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-900/50 border border-green-700 text-green-100 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-900/50 border border-red-700 text-red-100 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <main class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

        <footer class="mt-auto bg-gray-900 border-t border-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
                <p class="text-center text-sm text-gray-500">
                    "Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang."
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
