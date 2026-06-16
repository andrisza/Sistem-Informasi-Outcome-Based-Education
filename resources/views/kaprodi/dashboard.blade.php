@extends('layouts.app')

@section('title', 'Dashboard Kaprodi')
@section('header', 'Dashboard')

@section('content')

{{-- ── Stat cards ── --}}
<div class="grid grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
    @php
        $stats = [
            ['label' => 'Total Pengguna',    'value' => $totalUsers,      'sub' => 'aktif di sistem',        'color' => 'blue',   'icon' => 'users'],
            ['label' => 'Kurikulum Aktif',   'value' => $kurikulumAktif,  'sub' => 'periode berjalan',       'color' => 'violet', 'icon' => 'book-open'],
            ['label' => 'RPS Pending',       'value' => $rpsPending,      'sub' => 'menunggu persetujuan',   'color' => 'amber',  'icon' => 'document-check'],
        ];
        $colorMap = [
            'blue'    => ['icon' => 'bg-blue-100 text-blue-600',    'val' => 'text-blue-700'],
            'violet'  => ['icon' => 'bg-violet-100 text-violet-600','val' => 'text-violet-700'],
            'amber'   => ['icon' => 'bg-amber-100 text-amber-600',  'val' => 'text-amber-700'],
        ];
    @endphp

    @foreach ($stats as $stat)
        @php $c = $colorMap[$stat['color']]; @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $c['icon'] }}">
                @include('layouts._icon', ['name' => $stat['icon'], 'class' => 'w-4 h-4'])
            </div>
            <div class="min-w-0">
                <p class="text-xl font-bold {{ $c['val'] }}">{{ $stat['value'] }}</p>
                <p class="text-xs font-medium text-gray-700 mt-0.5 leading-tight">{{ $stat['label'] }}</p>
                <p class="text-xs text-gray-400">{{ $stat['sub'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- ── Main grid ── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

    {{-- RPS Pending --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm flex flex-col">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">RPS Menunggu Persetujuan</h3>
            <a href="{{ route('kaprodi.rps-approval.index') }}"
               class="text-xs text-blue-600 hover:underline font-medium">Lihat semua →</a>
        </div>
        <div class="divide-y divide-gray-50 flex-1">
            @forelse ($rpsPendingList as $rps)
                <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50/60 transition-colors">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            {{ $rps->mataKuliah?->nama_mk ?? '—' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <span class="font-mono text-gray-500">{{ $rps->mataKuliah?->kode_mk ?? '' }}</span>
                            · {{ $rps->dosenPengembang?->name ?? '—' }}
                            · {{ $rps->semester?->nama ?? '—' }}
                        </p>
                    </div>
                    <a href="{{ route('kaprodi.rps-approval.show', $rps) }}"
                       class="ml-4 shrink-0 text-xs bg-amber-100 text-amber-700 font-medium px-3 py-1.5 rounded-full hover:bg-amber-200 transition-colors">
                        Review
                    </a>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-sm text-gray-400">
                    <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tidak ada RPS yang menunggu persetujuan.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Aktivitas terbaru --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex flex-col">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Aktivitas Terbaru</h3>
            <a href="{{ route('kaprodi.activity-log.index') }}"
               class="text-xs text-blue-600 hover:underline font-medium">Semua →</a>
        </div>
        <div class="px-5 py-3 space-y-3 flex-1 overflow-y-auto" style="max-height: 320px">
            @forelse ($recentActivity as $log)
                <div class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 text-xs font-bold uppercase">
                        {{ mb_substr($log->user?->name ?? '?', 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-gray-700 leading-snug">
                            <span class="font-medium">{{ $log->user?->name ?? 'System' }}</span>
                            {{ $log->action }}
                            <span class="text-gray-400">{{ class_basename($log->model_type) }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-6 text-center">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection
