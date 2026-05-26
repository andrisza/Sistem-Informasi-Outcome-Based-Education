@extends('layouts.app')

@section('title', 'Arsip Rapat')
@section('header', 'Dokumentasi Rapat Kurikulum')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Arsip Rapat</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.arsip-rapat.create', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Arsip
        </a>
    @endif
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
@endphp

{{-- Filter bar --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <form method="GET" action="{{ route('kurikulum.arsip-rapat.index', $kurikulum) }}"
          class="flex flex-wrap items-center gap-3 px-4 py-3">
        <select name="jenis"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-700">
            <option value="">Semua Jenis Rapat</option>
            @foreach ($jenisLabel as $key => $cfg)
                <option value="{{ $key }}" {{ request('jenis') === $key ? 'selected' : '' }}>
                    {{ $cfg['label'] }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            @include('layouts._icon', ['name' => 'filter', 'class' => 'w-3.5 h-3.5'])
            Filter
        </button>
        @if (request()->anyFilled(['jenis']))
            <a href="{{ route('kurikulum.arsip-rapat.index', $kurikulum) }}"
               class="text-sm text-gray-400 hover:text-gray-600">Reset</a>
        @endif

        {{-- Summary stats --}}
        <div class="ml-auto flex flex-wrap items-center gap-2">
            @foreach ($jenisLabel as $key => $cfg)
                @php $cnt = $jenisCounts[$key] ?? 0; @endphp
                @if ($cnt > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $jenisColors[$cfg['color']] }}"
                          data-tooltip="Terdapat {{ $cnt }} dokumen {{ $cfg['label'] }}"
                          data-tip-label="Jenis Rapat">
                        {{ $cnt }} {{ $cfg['label'] }}
                    </span>
                @endif
            @endforeach
        </div>
    </form>
</div>

{{-- Card / Table view --}}
@if ($arsipList->isEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-16 text-center">
        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
            @include('layouts._icon', ['name' => 'meeting', 'class' => 'w-6 h-6 text-gray-400'])
        </div>
        <p class="text-gray-500 font-medium text-sm">Belum ada arsip rapat.</p>
        @if (!$kurikulum->isArsip())
            <p class="text-xs text-gray-400 mt-1">
                <a href="{{ route('kurikulum.arsip-rapat.create', $kurikulum) }}" class="text-blue-600 hover:underline font-medium">Tambah arsip rapat pertama →</a>
            </p>
        @endif
    </div>
@else
    <div class="space-y-3">
        @foreach ($arsipList as $arsip)
            @php
                $jCfg = $jenisLabel[$arsip->jenis_rapat] ?? ['label' => $arsip->jenis_rapat, 'color' => 'gray'];
                $jColor = $jenisColors[$jCfg['color']];

                // Checklist: completeness of document fields
                $fields = [
                    'agenda'        => ['label' => 'Agenda',        'val' => $arsip->agenda],
                    'notulen'       => ['label' => 'Notulen',       'val' => $arsip->notulen],
                    'kesimpulan'    => ['label' => 'Kesimpulan',     'val' => $arsip->kesimpulan],
                    'tindak_lanjut' => ['label' => 'Tindak Lanjut', 'val' => $arsip->tindak_lanjut],
                ];
                $fieldsDone = collect($fields)->filter(fn($f) => !empty($f['val']))->count();
                $fieldsTotal = count($fields);
            @endphp

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:border-blue-200 hover:shadow-md transition-all">
                <div class="flex items-start gap-4 px-5 py-4">

                    {{-- Date badge --}}
                    <div class="shrink-0 text-center bg-slate-50 border border-slate-100 rounded-lg px-3 py-2 min-w-[56px]">
                        <p class="text-xs font-bold text-slate-600">{{ $arsip->tanggal_rapat?->format('d') ?? '—' }}</p>
                        <p class="text-[10px] text-slate-400 uppercase">{{ $arsip->tanggal_rapat?->format('M Y') ?? '' }}</p>
                    </div>

                    {{-- Main content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-1.5">
                            <div class="min-w-0">
                                <a href="{{ route('kurikulum.arsip-rapat.show', [$kurikulum, $arsip]) }}"
                                   class="font-semibold text-gray-800 text-sm hover:text-blue-600 transition-colors block truncate">
                                    {{ $arsip->judul_rapat }}
                                </a>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $jColor }}">
                                        {{ $jCfg['label'] }}
                                    </span>
                                    @if ($arsip->tempat)
                                        <span class="text-xs text-gray-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $arsip->tempat }}
                                        </span>
                                    @endif
                                    @if ($arsip->pembuat)
                                        <span class="text-xs text-gray-400">oleh {{ $arsip->pembuat->name }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Document completeness --}}
                            <div class="shrink-0 flex items-center gap-2"
                                 data-tooltip="Kelengkapan dokumen: {{ $fieldsDone }}/{{ $fieldsTotal }} bagian terisi (Agenda, Notulen, Kesimpulan, Tindak Lanjut)"
                                 data-tip-label="Kelengkapan Dokumen">
                                <div class="flex items-center gap-0.5">
                                    @foreach ($fields as $fKey => $fData)
                                        <div class="w-2.5 h-2.5 rounded-sm {{ !empty($fData['val']) ? 'bg-emerald-500' : 'bg-gray-200' }}"
                                             title="{{ $fData['label'] }}: {{ !empty($fData['val']) ? 'Terisi' : 'Kosong' }}"></div>
                                    @endforeach
                                </div>
                                <span class="text-xs text-gray-400">{{ $fieldsDone }}/{{ $fieldsTotal }}</span>
                            </div>
                        </div>

                        {{-- Field indicators --}}
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            @foreach ($fields as $fKey => $fData)
                                <span class="inline-flex items-center gap-1 text-[10px] font-medium {{ !empty($fData['val']) ? 'text-emerald-600' : 'text-gray-300' }}">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ !empty($fData['val']) ? 2.5 : 1.5 }}">
                                        @if (!empty($fData['val']))
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        @else
                                            <circle cx="12" cy="12" r="9"/>
                                        @endif
                                    </svg>
                                    {{ $fData['label'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('kurikulum.arsip-rapat.show', [$kurikulum, $arsip]) }}"
                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        @if (!$kurikulum->isArsip())
                            <a href="{{ route('kurikulum.arsip-rapat.edit', [$kurikulum, $arsip]) }}"
                               class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('kurikulum.arsip-rapat.destroy', [$kurikulum, $arsip]) }}"
                                  onsubmit="return confirm('Hapus arsip rapat ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($arsipList->hasPages())
        <div class="mt-5">{{ $arsipList->links() }}</div>
    @endif
@endif

@endsection
