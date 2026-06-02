@extends('layouts.app')

@section('title', $kurikulum->nama_kurikulum)
@section('header', $kurikulum->nama_kurikulum)

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $kurikulum->kode }}</span>
@endsection

@section('header-actions')
    {{-- Tombol Edit: tampil jika belum arsip --}}
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.edit', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
    @endif

    @if (auth()->user()->isKaprodi())
        @if ($kurikulum->isDraft())
            {{-- Draft → tampilkan tombol Aktifkan --}}
            <form method="POST" action="{{ route('kurikulum.aktifkan', $kurikulum) }}" class="inline"
                  onsubmit="return confirm('Aktifkan kurikulum {{ $kurikulum->kode }}?\n\nKurikulum akan berlaku aktif dan dapat digunakan oleh Dosen dan Mahasiswa.')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 hover:bg-blue-100 text-blue-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Aktifkan
                </button>
            </form>

        @elseif ($kurikulum->isAktif())
            {{-- Aktif → tampilkan tombol Arsipkan --}}
            <form method="POST" action="{{ route('kurikulum.arsip', $kurikulum) }}" class="inline"
                  onsubmit="return confirm('Arsipkan kurikulum {{ $kurikulum->kode }}?\n\nSetelah diarsipkan:\n• Semua data menjadi read-only\n• Tidak dapat diedit kembali kecuali diaktifkan ulang\n• Data tetap dapat dilihat dan dilaporkan')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 hover:bg-amber-100 text-amber-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/>
                    </svg>
                    Arsipkan
                </button>
            </form>

        @elseif ($kurikulum->isArsip())
            {{-- Arsip → tampilkan tombol Aktifkan Kembali --}}
            <form method="POST" action="{{ route('kurikulum.aktifkan', $kurikulum) }}" class="inline"
                  onsubmit="return confirm('Aktifkan kembali kurikulum {{ $kurikulum->kode }}?\n\nKurikulum akan dapat diedit kembali.')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-green-50 border border-green-200 hover:bg-green-100 text-green-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Aktifkan Kembali
                </button>
            </form>
        @endif

    @else
        {{-- Tim Kurikulum: tampilkan info status saja, tanpa tombol aksi status --}}
        @if ($kurikulum->isDraft())
            <span class="inline-flex items-center gap-1.5 text-xs text-amber-600 bg-amber-50 border border-amber-200 px-3 py-2 rounded-lg">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Menunggu aktivasi oleh Kaprodi
            </span>
        @elseif ($kurikulum->isArsip())
            <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 bg-gray-50 border border-gray-200 px-3 py-2 rounded-lg">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Kurikulum telah diarsipkan — read-only
            </span>
        @endif
    @endif
@endsection

@section('content')

{{-- ── Header singkat: identitas + counter inline ────────────────────── --}}
@php
    $sColor = match($kurikulum->status) {
        'aktif' => 'bg-green-100 text-green-700',
        'arsip' => 'bg-gray-100 text-gray-500',
        'draft' => 'bg-amber-100 text-amber-700',
        default => 'bg-gray-100 text-gray-500',
    };
@endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-4 flex flex-wrap items-center gap-x-6 gap-y-3">
    <div class="flex items-center gap-2 min-w-0">
        <span class="text-xs font-mono text-gray-400">{{ $kurikulum->kode }}</span>
        <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-700">{{ $kurikulum->jenjang }}</span>
        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium {{ $sColor }}">{{ ucfirst($kurikulum->status) }}</span>
        <span class="text-xs text-gray-500 truncate">{{ $kurikulum->program_studi }} · {{ $kurikulum->tahun_mulai }}{{ $kurikulum->tahun_selesai ? '–' . $kurikulum->tahun_selesai : '' }}</span>
    </div>
    <div class="flex items-center gap-4 ml-auto text-xs">
        @foreach ([
            ['PL', $kurikulum->pl_count, 'text-blue-700'],
            ['CPL', $kurikulum->cpl_prodi_count, 'text-violet-700'],
            ['BK', $kurikulum->bahan_kajian_count, 'text-emerald-700'],
            ['MK', $kurikulum->mata_kuliah_count, 'text-amber-700'],
        ] as [$lbl, $val, $cls])
            <span class="flex items-baseline gap-1">
                <span class="font-bold text-base {{ $cls }}">{{ $val }}</span>
                <span class="text-gray-400">{{ $lbl }}</span>
            </span>
        @endforeach
    </div>
</div>

{{-- ── Checklist OBE (esensi penyusunan kurikulum) ─────────────────── --}}
@php
    $steps = [
        ['no'=>1, 'label'=>'Profil Lulusan',  'count'=>$kurikulum->pl_count,           'min'=>1, 'color'=>'blue',    'route'=>route('kurikulum.pl.index', $kurikulum)],
        ['no'=>2, 'label'=>'CPL Prodi',       'count'=>$kurikulum->cpl_prodi_count,    'min'=>1, 'color'=>'violet',  'route'=>route('kurikulum.cpl-prodi.index', $kurikulum)],
        ['no'=>3, 'label'=>'Bahan Kajian',    'count'=>$kurikulum->bahan_kajian_count, 'min'=>1, 'color'=>'emerald', 'route'=>route('kurikulum.bahan-kajian.index', $kurikulum)],
        ['no'=>4, 'label'=>'Mata Kuliah',     'count'=>$kurikulum->mata_kuliah_count,  'min'=>1, 'color'=>'amber',   'route'=>route('kurikulum.mata-kuliah.index', $kurikulum)],
        ['no'=>5, 'label'=>'Matriks Pemetaan','count'=>null,                            'min'=>null,'color'=>'indigo','route'=>route('kurikulum.pivot.pl-cpl', $kurikulum)],
        ['no'=>6, 'label'=>'Distribusi MK',   'count'=>null,                            'min'=>null,'color'=>'teal',  'route'=>route('kurikulum.distribusi-semester', $kurikulum)],
    ];
    $cc = [
        'blue'=>'bg-blue-600',   'violet'=>'bg-violet-600', 'emerald'=>'bg-emerald-600',
        'amber'=>'bg-amber-600', 'indigo'=>'bg-indigo-600', 'teal'=>'bg-teal-600',
    ];
    $done = collect($steps)->filter(fn($s) => $s['min'] === null || $s['count'] >= $s['min'])->count();
    $total = count($steps);
    $pct = round(($done / $total) * 100);
@endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-4 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800">Checklist Penyusunan OBE</h3>
        <div class="flex items-center gap-2">
            <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-1.5 rounded-full {{ $done === $total ? 'bg-emerald-500' : 'bg-blue-500' }}" style="width:{{ $pct }}%"></div>
            </div>
            <span class="text-xs font-semibold {{ $done === $total ? 'text-emerald-700' : 'text-blue-700' }}">{{ $done }}/{{ $total }}</span>
        </div>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 divide-x divide-y sm:divide-y-0 divide-gray-100">
        @foreach ($steps as $step)
            @php $isDone = ($step['min'] === null) || ($step['count'] >= $step['min']); @endphp
            <a href="{{ $step['route'] }}"
               class="flex items-center gap-2 px-3 py-3 hover:bg-gray-50 transition-colors group">
                <div class="w-5 h-5 rounded-full shrink-0 flex items-center justify-center {{ $isDone ? $cc[$step['color']] . ' text-white' : 'bg-gray-100 text-gray-400' }}">
                    @if ($isDone)
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        <span class="text-[10px] font-bold">{{ $step['no'] }}</span>
                    @endif
                </div>
                <span class="text-xs font-medium text-gray-700 leading-tight">{{ $step['label'] }}</span>
                @if ($step['count'] !== null)
                    <span class="ml-auto text-xs font-bold {{ $isDone ? 'text-gray-600' : 'text-gray-400' }}">{{ $step['count'] }}</span>
                @endif
            </a>
        @endforeach
    </div>
</div>

{{-- ── Administrasi (compact) ──────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
    @foreach ([
        ['label'=>'Periode Penyusunan', 'sub'=>$kurikulum->periode_count.' periode',     'route'=>route('kurikulum.periode.index', $kurikulum),     'icon'=>'calendar'],
        ['label'=>'Tim Kurikulum',      'sub'=>'Anggota penyusun',                       'route'=>route('kurikulum.tim.index', $kurikulum),         'icon'=>'users'],
        ['label'=>'Arsip Rapat',        'sub'=>$kurikulum->arsip_rapat_count.' dokumen', 'route'=>route('kurikulum.arsip-rapat.index', $kurikulum), 'icon'=>'meeting'],
    ] as $a)
        <a href="{{ $a['route'] }}"
           class="flex items-center gap-3 bg-white border border-gray-100 hover:border-gray-300 rounded-xl px-4 py-3 shadow-sm transition-colors">
            <div class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0">
                @include('layouts._icon', ['name' => $a['icon'], 'class' => 'w-4 h-4'])
            </div>
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-800">{{ $a['label'] }}</p>
                <p class="text-xs text-gray-400">{{ $a['sub'] }}</p>
            </div>
        </a>
    @endforeach
</div>

{{-- ── Visi / Misi (collapsible) ───────────────────────────────────── --}}
@if ($kurikulum->visi || $kurikulum->misi || $kurikulum->tujuan || $kurikulum->sasaran)
<details class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <summary class="px-5 py-3 cursor-pointer text-sm font-semibold text-gray-700 hover:text-gray-900 select-none">
        Visi, Misi, Tujuan &amp; Sasaran
    </summary>
    <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-100 border-t border-gray-100">
        @foreach ([
            ['Visi', $kurikulum->visi],
            ['Misi', $kurikulum->misi],
            ['Tujuan', $kurikulum->tujuan],
            ['Sasaran', $kurikulum->sasaran],
        ] as [$lbl, $val])
            @if ($val)
                <div class="px-5 py-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">{{ $lbl }}</p>
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $val }}</p>
                </div>
            @endif
        @endforeach
    </div>
</details>
@endif

@endsection
