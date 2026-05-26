@extends('layouts.app')

@section('title', $arsipRapat->judul_rapat)
@section('header', 'Detail Arsip Rapat')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.arsip-rapat.index', $kurikulum) }}" class="hover:text-blue-600">Arsip Rapat</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ Str::limit($arsipRapat->judul_rapat, 40) }}</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.arsip-rapat.edit', [$kurikulum, $arsipRapat]) }}"
           class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
    @endif
    <a href="{{ route('kurikulum.arsip-rapat.index', $kurikulum) }}"
       class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        ← Kembali
    </a>
@endsection

@section('content')

@php
$jenisLabel = [
    'pleno_kurikulum'   => ['label' => 'Pleno Kurikulum',   'color' => 'blue'],
    'rapat_tim_kecil'   => ['label' => 'Rapat Tim Kecil',   'color' => 'violet'],
    'rapat_stakeholder' => ['label' => 'Rapat Stakeholder', 'color' => 'emerald'],
    'rapat_evaluasi'    => ['label' => 'Rapat Evaluasi',    'color' => 'amber'],
    'rapat_cqi'         => ['label' => 'Rapat CQI',         'color' => 'red'],
    'rapat_lainnya'     => ['label' => 'Rapat Lainnya',     'color' => 'gray'],
];
$jenisColors = [
    'blue'    => 'bg-blue-100 text-blue-700',
    'violet'  => 'bg-violet-100 text-violet-700',
    'emerald' => 'bg-emerald-100 text-emerald-700',
    'amber'   => 'bg-amber-100 text-amber-700',
    'red'     => 'bg-red-100 text-red-700',
    'gray'    => 'bg-gray-100 text-gray-600',
];
$jCfg   = $jenisLabel[$arsipRapat->jenis_rapat] ?? ['label' => $arsipRapat->jenis_rapat, 'color' => 'gray'];
$jColor = $jenisColors[$jCfg['color']];

$docFields = [
    'agenda'        => ['label' => 'Agenda',         'icon' => 'clipboard-list', 'desc' => 'Daftar agenda yang dibahas dalam rapat'],
    'notulen'       => ['label' => 'Notulen',        'icon' => 'pencil',         'desc' => 'Catatan resmi jalannya rapat'],
    'kesimpulan'    => ['label' => 'Kesimpulan',     'icon' => 'check-circle',   'desc' => 'Keputusan dan kesepakatan yang dicapai'],
    'tindak_lanjut' => ['label' => 'Tindak Lanjut',  'icon' => 'trending-up',    'desc' => 'Rencana aksi dan penanggung jawab'],
];
$fieldsDone = collect($docFields)->filter(fn($f, $k) => !empty($arsipRapat->$k))->count();
$fieldsTotal = count($docFields);
@endphp

<div class="max-w-4xl space-y-5">

    {{-- Header card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    @include('layouts._icon', ['name' => 'meeting', 'class' => 'w-4 h-4 text-slate-600'])
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800 text-sm">{{ $arsipRapat->judul_rapat }}</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $jColor }} mt-0.5">
                        {{ $jCfg['label'] }}
                    </span>
                </div>
            </div>
            {{-- Document completeness badge --}}
            <div class="text-right"
                 data-tooltip="Kelengkapan: {{ $fieldsDone }}/{{ $fieldsTotal }} bagian dokumen terisi"
                 data-tip-label="Kelengkapan Dokumen">
                <div class="flex items-center gap-1.5 justify-end mb-0.5">
                    @foreach ($docFields as $fKey => $fData)
                        <div class="w-3 h-3 rounded {{ !empty($arsipRapat->$fKey) ? 'bg-emerald-500' : 'bg-gray-200' }}"
                             title="{{ $fData['label'] }}"></div>
                    @endforeach
                </div>
                <span class="text-xs {{ $fieldsDone === $fieldsTotal ? 'text-emerald-600 font-semibold' : 'text-gray-400' }}">
                    {{ $fieldsDone === $fieldsTotal ? 'Dokumen Lengkap ✓' : $fieldsDone . '/' . $fieldsTotal . ' terisi' }}
                </span>
            </div>
        </div>

        {{-- Meta info grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-100 border-b border-gray-100">
            <div class="px-5 py-3.5">
                <p class="text-xs text-gray-400 mb-0.5">Tanggal</p>
                <p class="text-sm font-semibold text-gray-800">{{ $arsipRapat->tanggal_rapat?->format('d F Y') ?? '–' }}</p>
            </div>
            <div class="px-5 py-3.5">
                <p class="text-xs text-gray-400 mb-0.5">Tempat</p>
                <p class="text-sm font-semibold text-gray-800">{{ $arsipRapat->tempat ?: '–' }}</p>
            </div>
            @if ($arsipRapat->periode)
            <div class="px-5 py-3.5">
                <p class="text-xs text-gray-400 mb-0.5">Periode</p>
                <p class="text-sm font-semibold text-gray-800">{{ $arsipRapat->periode->nama_periode }}</p>
            </div>
            @endif
            <div class="px-5 py-3.5">
                <p class="text-xs text-gray-400 mb-0.5">Dibuat oleh</p>
                <p class="text-sm font-semibold text-gray-800">{{ $arsipRapat->pembuat?->name ?? '–' }}</p>
                <p class="text-xs text-gray-400">{{ $arsipRapat->created_at?->format('d M Y, H:i') }}</p>
            </div>
        </div>

        {{-- Document checklist bar --}}
        <div class="px-5 py-3 bg-gray-50">
            <div class="flex items-center gap-4">
                <span class="text-xs text-gray-500 font-medium shrink-0">Bagian Dokumen:</span>
                <div class="flex items-center gap-3">
                    @foreach ($docFields as $fKey => $fData)
                        @php $isDone = !empty($arsipRapat->$fKey); @endphp
                        <span class="inline-flex items-center gap-1 text-xs font-medium {{ $isDone ? 'text-emerald-700' : 'text-gray-400' }}"
                              data-tooltip="{{ $fData['desc'] }}"
                              data-tip-label="{{ $fData['label'] }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $isDone ? 2.5 : 1.5 }}">
                                @if ($isDone)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                @endif
                            </svg>
                            {{ $fData['label'] }}
                        </span>
                    @endforeach
                </div>
                @if (!$kurikulum->isArsip() && $fieldsDone < $fieldsTotal)
                    <a href="{{ route('kurikulum.arsip-rapat.edit', [$kurikulum, $arsipRapat]) }}"
                       class="ml-auto text-xs text-blue-600 hover:underline font-medium shrink-0">
                        Lengkapi dokumen →
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Document sections --}}
    @foreach ($docFields as $fKey => $fData)
        @php $content = $arsipRapat->$fKey; @endphp
        <div class="bg-white rounded-xl border {{ !empty($content) ? 'border-gray-100' : 'border-dashed border-gray-200' }} shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3.5 border-b {{ !empty($content) ? 'border-gray-100 bg-white' : 'border-gray-100 bg-gray-50' }}">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ !empty($content) ? 'bg-emerald-100' : 'bg-gray-100' }}">
                        @include('layouts._icon', ['name' => $fData['icon'], 'class' => 'w-3.5 h-3.5 ' . (!empty($content) ? 'text-emerald-600' : 'text-gray-400')])
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold {{ !empty($content) ? 'text-gray-800' : 'text-gray-500' }} uppercase tracking-wider">
                            {{ $fData['label'] }}
                        </h4>
                        <p class="text-[10px] text-gray-400">{{ $fData['desc'] }}</p>
                    </div>
                </div>
                @if (!empty($content))
                    <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-medium">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Terisi
                    </span>
                @else
                    <span class="text-xs text-gray-300">Belum terisi</span>
                @endif
            </div>
            <div class="px-5 py-4">
                @if (!empty($content))
                    <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $content }}</p>
                @else
                    <p class="text-sm text-gray-400 italic">Bagian ini belum diisi.
                        @if (!$kurikulum->isArsip())
                            <a href="{{ route('kurikulum.arsip-rapat.edit', [$kurikulum, $arsipRapat]) }}"
                               class="text-blue-600 hover:underline not-italic font-medium ml-1">Isi sekarang →</a>
                        @endif
                    </p>
                @endif
            </div>
        </div>
    @endforeach

</div>

@endsection
