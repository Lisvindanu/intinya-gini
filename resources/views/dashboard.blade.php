@extends('layouts.app')

@section('title', 'Dashboard - Intinya Gini')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div>
        @include('components.topic-form')
    </div>

    <div>
        <div class="bg-gray-900 rounded-lg border border-gray-800 p-6">
            <h2 class="text-xl font-bold text-white mb-4">TL;DR Terbaru</h2>

            @if($scripts->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <p>Belum ada TL;DR yang dibuat.</p>
                    <p class="text-sm mt-2">Buat yang pertama sekarang!</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($scripts as $script)
                        <x-result-card :script="$script" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
