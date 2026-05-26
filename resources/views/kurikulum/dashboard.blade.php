@extends('layouts.app')

@section('title', 'Dashboard Kurikulum')
@section('header', 'Dashboard Kurikulum')

@section('header-actions')
    <a href="{{ route('kurikulum.index') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        @include('layouts._icon', ['name' => 'book-open', 'class' => 'w-4 h-4'])
        Semua Kurikulum
    </a>
@endsection

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    @php
        $stats = [
            ['label' => 'Total Kurikulum', 'value' => $totalKurikulum, 'sub' => 'semua status',        'color' => 'blue',   'icon' => 'book-open'],
            ['label' => 'Kurikulum Aktif',  'value' => $kurikulumAktif, 'sub' => 'sedang digunakan',   'color' => 'emerald','icon' => 'document-check'],
            ['label' => 'Total Mata Kuliah','value' => $totalMk,        'sub' => 'semua kurikulum',    'color' => 'violet', 'icon' => 'document'],
            ['label' => 'Total Dosen',      'value' => $totalDosen,     'sub' => 'terdaftar di sistem','color' => 'amber',  'icon' => 'users'],
        ];
        $colorMap = [
            'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'bg-blue-100 text-blue-600',     'val' => 'text-blue-700'],
            'violet' => ['bg' => 'bg-violet-50',  'icon' => 'bg-violet-100 text-violet-600', 'val' => 'text-violet-700'],
            'amber'  => ['bg' => 'bg-amber-50',   'icon' => 'bg-amber-100 text-amber-600',   'val' => 'text-amber-700'],
            'emerald'=> ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-100 text-emerald-600','val' => 'text-emerald-700'],
        ];
    @endphp

    @foreach ($stats as $stat)
        @php $c = $colorMap[$stat['color']]; @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 {{ $c['icon'] }}">
                @include('layouts._icon', ['name' => $stat['icon'], 'class' => 'w-5 h-5'])
            </div>
            <div class="min-w-0">
                <p class="text-2xl font-bold {{ $c['val'] }}">{{ $stat['value'] }}</p>
                <p class="text-sm font-medium text-gray-700 mt-0.5">{{ $stat['label'] }}</p>
                <p class="text-xs text-gray-400">{{ $stat['sub'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- Daftar kurikulum aktif --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 text-sm">Kurikulum Aktif</h3>
        <a href="{{ route('kurikulum.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Lihat semua →</a>
    </div>

    @forelse ($kurikulumList as $k)
        <div class="px-5 py-4 border-b border-gray-50 last:border-0">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-mono text-gray-500">{{ $k->kode }}</span>
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">{{ $k->jenjang }}</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-800">{{ $k->nama_kurikulum }}</p>
                    <p class="text-xs text-gray-500">{{ $k->program_studi }} · {{ $k->tahun_mulai }}{{ $k->tahun_selesai ? '–' . $k->tahun_selesai : ' – kini' }}</p>
                </div>
                <div class="flex items-center gap-4 shrink-0 text-xs text-gray-500">
                    <span><strong class="text-gray-700">{{ $k->pl_count }}</strong> PL</span>
                    <span><strong class="text-gray-700">{{ $k->cpl_prodi_count }}</strong> CPL</span>
                    <span><strong class="text-gray-700">{{ $k->mata_kuliah_count }}</strong> MK</span>
                    <a href="{{ route('kurikulum.show', $k) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors text-xs font-medium">
                        Kelola
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="px-5 py-10 text-center text-sm text-gray-400">
            Belum ada kurikulum aktif.
            <a href="{{ route('kurikulum.create') }}" class="text-blue-600 hover:underline ml-1">Buat sekarang</a>
        </div>
    @endforelse
</div>

{{-- Quick links --}}
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">
    @php
        $links = [
            ['label' => 'Buat Kurikulum', 'href' => route('kurikulum.create'),  'icon' => 'document', 'color' => 'blue'],
            ['label' => 'Daftar Kurikulum','href' => route('kurikulum.index'),  'icon' => 'book-open', 'color' => 'violet'],
        ];
        $qlColors = [
            'blue'   => 'bg-blue-50 hover:bg-blue-100 text-blue-700',
            'violet' => 'bg-violet-50 hover:bg-violet-100 text-violet-700',
        ];
    @endphp
    @foreach ($links as $link)
        <a href="{{ $link['href'] }}"
           class="flex flex-col items-center gap-2 px-4 py-5 rounded-xl border border-gray-100 shadow-sm {{ $qlColors[$link['color']] }} transition-colors text-center">
            @include('layouts._icon', ['name' => $link['icon'], 'class' => 'w-6 h-6'])
            <span class="text-xs font-medium">{{ $link['label'] }}</span>
        </a>
    @endforeach
</div>

@endsection
