@extends('layouts.app')

@section('title', 'Notifikasi')
@section('header', 'Notifikasi')

@section('header-actions')
    <form method="POST" action="{{ route('notifikasi.read-all') }}">
        @csrf
        <button type="submit"
                class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Tandai Semua Dibaca
        </button>
    </form>
@endsection

@section('content')

<div class="space-y-2">
    @forelse ($notifikasi as $notif)
        @php
            $tipeCls = match($notif->tipe) {
                'success' => 'border-green-200 bg-green-50',
                'warning' => 'border-amber-200 bg-amber-50',
                'review'  => 'border-violet-200 bg-violet-50',
                default   => 'border-blue-200 bg-blue-50',
            };
            $dotCls = match($notif->tipe) {
                'success' => 'bg-green-500',
                'warning' => 'bg-amber-500',
                'review'  => 'bg-violet-500',
                default   => 'bg-blue-500',
            };
            $titleCls = match($notif->tipe) {
                'success' => 'text-green-800',
                'warning' => 'text-amber-800',
                'review'  => 'text-violet-800',
                default   => 'text-blue-800',
            };
        @endphp
        <div class="flex items-start gap-3 rounded-xl border {{ $tipeCls }} px-4 py-3 {{ $notif->dibaca ? 'opacity-60' : '' }}">
            <div class="mt-1.5 shrink-0 w-2 h-2 rounded-full {{ $dotCls }} {{ $notif->dibaca ? 'opacity-30' : '' }}"></div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm {{ $titleCls }}">{{ $notif->judul }}</p>
                <p class="text-sm text-gray-600 mt-0.5">{{ $notif->pesan }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at?->diffForHumans() ?? '' }}</p>
            </div>
            @if (!$notif->dibaca)
            <form method="POST" action="{{ route('notifikasi.read', $notif) }}" class="shrink-0">
                @csrf
                <button type="submit"
                        class="text-xs text-gray-500 hover:text-gray-800 px-2 py-1 rounded hover:bg-white/60 transition-colors" title="Tandai dibaca">
                    ✓
                </button>
            </form>
            @endif
        </div>
    @empty
        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-sm">Tidak ada notifikasi.</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-4">
    {{ $notifikasi->links() }}
</div>

@endsection
