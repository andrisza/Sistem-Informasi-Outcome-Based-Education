@extends('layouts.app')

@section('title', 'Organisasi MK – ' . $kurikulum->kode)
@section('header', 'Organisasi Mata Kuliah per Semester')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Organisasi MK</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.organisasi-mk.export', $kurikulum)])
@endsection

@section('content')

@php
    $semesterList = range(8, 1);
    $bySmt = $mkList->groupBy('semester');

    $maxWajib = 0; $maxPil = 0; $maxMkwk = 0; $maxMkdu = 0;
    foreach ($semesterList as $smt) {
        $mks = $bySmt->get($smt, collect());
        $maxWajib = max($maxWajib, $mks->where('kategori_mk', 'Wajib')->count());
        $maxPil   = max($maxPil,   $mks->where('kategori_mk', 'Pilihan')->count());
        $maxMkwk  = max($maxMkwk,  $mks->where('kategori_mk', 'MKWK')->count());
        $maxMkdu  = max($maxMkdu,  $mks->where('kategori_mk', 'MKDU')->count());
    }
    $maxWajib = max($maxWajib, 1);
    $maxPil   = max($maxPil,   1);
    $maxMkwk  = max($maxMkwk,  1);
    $maxMkdu  = max($maxMkdu,  1);

    $totalSksAll    = $mkList->sum(fn($m) => ($m->sks_teori ?? 0) + ($m->sks_praktikum ?? 0));
    $belumDipetakan = $mkList->filter(fn($m) => empty($m->kategori_mk));
    $isEditable     = !$kurikulum->isArsip();
@endphp

{{-- Statistik --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Total MK</p>
        <p class="text-2xl font-black text-gray-800 mt-0.5">{{ $mkList->count() }}</p>
        <p class="text-[10px] text-gray-400">{{ $totalSksAll }} total SKS</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">MK Wajib</p>
        <p class="text-2xl font-black text-blue-600 mt-0.5">{{ $mkList->where('kategori_mk', 'Wajib')->count() }}</p>
        <p class="text-[10px] text-gray-400">{{ $mkList->where('kategori_mk', 'Wajib')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">MK Pilihan</p>
        <p class="text-2xl font-black text-indigo-600 mt-0.5">{{ $mkList->where('kategori_mk', 'Pilihan')->count() }}</p>
        <p class="text-[10px] text-gray-400">{{ $mkList->where('kategori_mk', 'Pilihan')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">MKWK + MKDU</p>
        <p class="text-2xl font-black text-emerald-600 mt-0.5">
            {{ $mkList->whereIn('kategori_mk', ['MKWK', 'MKDU'])->count() }}
        </p>
        <p class="text-[10px] text-gray-400">{{ $mkList->whereIn('kategori_mk', ['MKWK','MKDU'])->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS</p>
    </div>
</div>

@if ($isEditable)
<p class="mb-3 text-xs text-gray-500 flex items-center gap-1.5">
    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Klik sel pada grid untuk memetakan atau mengubah kategori MK. Arahkan kursor ke sel MK untuk melihat nama lengkapnya.
</p>
@endif

{{-- Legend --}}
<div class="flex flex-wrap gap-2 mb-3 items-center text-xs">
    <span class="text-gray-400 font-medium text-[11px]">Kategori:</span>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg font-semibold text-xs shadow-sm" style="background:#3B82F6;color:#fff">MK Wajib</span>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg font-semibold text-xs shadow-sm" style="background:#6366F1;color:#fff">MK Pilihan</span>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg font-semibold text-xs shadow-sm" style="background:#10B981;color:#fff">MKWK</span>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg font-semibold text-xs shadow-sm" style="background:#F59E0B;color:#fff">MKDU</span>
    @if ($isEditable)
    <span class="text-gray-400 text-[10px] ml-1">· Klik sel untuk memetakan MK</span>
    @endif
</div>

{{-- Grid --}}
<div class="rounded-xl border border-gray-200 shadow-sm overflow-hidden bg-white">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs" style="min-width: max-content; width: 100%">
            <thead>
                {{-- Row 1: Kategori header --}}
                <tr>
                    <th colspan="3"
                        class="px-3 py-2.5 text-center border border-slate-600"
                        style="background:#1E293B"></th>
                    <th colspan="{{ $maxWajib }}"
                        class="px-3 py-2 text-center text-xs font-bold uppercase tracking-wide border"
                        style="background:#3B82F6;color:#fff;border-color:#2563EB">
                        MK WAJIB
                    </th>
                    <th colspan="{{ $maxPil }}"
                        class="px-3 py-2 text-center text-xs font-bold uppercase tracking-wide border"
                        style="background:#6366F1;color:#fff;border-color:#4F46E5">
                        MK PILIHAN
                    </th>
                    <th colspan="{{ $maxMkwk }}"
                        class="px-3 py-2 text-center text-xs font-bold uppercase tracking-wide border"
                        style="background:#10B981;color:#fff;border-color:#059669">
                        MKWK
                    </th>
                    <th colspan="{{ $maxMkdu }}"
                        class="px-3 py-2 text-center text-xs font-bold uppercase tracking-wide border"
                        style="background:#F59E0B;color:#fff;border-color:#D97706">
                        MKDU
                    </th>
                </tr>
                {{-- Row 2: Sub-headers --}}
                <tr>
                    <th class="px-3 py-2 text-center text-[11px] font-bold border border-slate-500 w-12"
                        style="background:#334155;color:#F1F5F9">Smt</th>
                    <th class="px-3 py-2 text-center text-[11px] font-bold border border-slate-500 w-14"
                        style="background:#334155;color:#F1F5F9">SKS</th>
                    <th class="px-3 py-2 text-center text-[11px] font-bold border border-slate-500 w-14"
                        style="background:#334155;color:#F1F5F9">Jml</th>
                    @for ($i = 0; $i < $maxWajib; $i++)
                        <th class="px-2 py-2 border" style="background:#DBEAFE;border-color:#93C5FD;min-width:88px"></th>
                    @endfor
                    @for ($i = 0; $i < $maxPil; $i++)
                        <th class="px-2 py-2 border" style="background:#E0E7FF;border-color:#A5B4FC;min-width:88px"></th>
                    @endfor
                    @for ($i = 0; $i < $maxMkwk; $i++)
                        <th class="px-2 py-2 border" style="background:#D1FAE5;border-color:#6EE7B7;min-width:88px"></th>
                    @endfor
                    @for ($i = 0; $i < $maxMkdu; $i++)
                        <th class="px-2 py-2 border" style="background:#FEF3C7;border-color:#FCD34D;min-width:88px"></th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @php $romawi = ['','I','II','III','IV','V','VI','VII','VIII']; @endphp
                @foreach ($semesterList as $smt)
                    @php
                        $mks      = $bySmt->get($smt, collect());
                        $wajibMks = $mks->where('kategori_mk', 'Wajib')->values();
                        $pilMks   = $mks->where('kategori_mk', 'Pilihan')->values();
                        $mkwkMks  = $mks->where('kategori_mk', 'MKWK')->values();
                        $mkduMks  = $mks->where('kategori_mk', 'MKDU')->values();
                        $smtSks   = $mks->sum(fn($m) => $m->sks_teori + $m->sks_praktikum);
                        $rowBg    = $smt % 2 === 0 ? '#F8FAFC' : '#FFFFFF';
                    @endphp
                    <tr style="background:{{ $rowBg }}">
                        {{-- Semester label --}}
                        <td class="px-3 py-3 text-center font-bold text-sm border border-slate-500"
                            style="background:#334155;color:#fff;min-width:48px">
                            {{ $romawi[$smt] }}
                        </td>
                        {{-- SKS total --}}
                        <td class="px-3 py-3 text-center border border-slate-300"
                            style="background:#E2E8F0">
                            <span class="font-bold text-xs" style="color:#1E293B">{{ $smtSks }}</span>
                        </td>
                        {{-- Jumlah MK --}}
                        <td class="px-3 py-3 text-center border border-slate-300"
                            style="background:#E2E8F0">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold"
                                  style="background:#334155;color:#fff">
                                {{ $mks->count() }}
                            </span>
                        </td>

                        {{-- ── MK Wajib ── --}}
                        @for ($i = 0; $i < $maxWajib; $i++)
                            @php $mk = $wajibMks[$i] ?? null; @endphp
                            <td class="border text-center align-middle p-0 {{ $isEditable ? 'mk-cell cursor-pointer' : '' }}"
                                style="min-width:88px;height:48px;border-color:#93C5FD;background:{{ $mk ? '#DBEAFE' : '#EFF6FF' }}"
                                @if($isEditable) data-smt="{{ $smt }}" data-kategori="Wajib" @endif
                                @if($mk) data-mk-name="{{ $mk->nama_mk }}" data-mk-sub="Smt {{ $smt }} · {{ $mk->sks_total }} SKS" @endif>
                                @if ($mk)
                                    <div class="px-2 py-1.5 select-none w-full h-full flex flex-col items-center justify-center gap-0.5
                                                hover:bg-blue-200 transition-colors cursor-default">
                                        <span class="font-mono font-bold text-blue-900 text-[11px] leading-none">{{ $mk->kode_mk }}</span>
                                        <span class="text-[9px] text-blue-700 font-medium leading-none mt-0.5">{{ $mk->sks_total }} SKS</span>
                                    </div>
                                @elseif ($isEditable)
                                    <div class="flex items-center justify-center w-full h-full hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                        @endfor

                        {{-- ── MK Pilihan ── --}}
                        @for ($i = 0; $i < $maxPil; $i++)
                            @php $mk = $pilMks[$i] ?? null; @endphp
                            <td class="border text-center align-middle p-0 {{ $isEditable ? 'mk-cell cursor-pointer' : '' }}"
                                style="min-width:88px;height:48px;border-color:#A5B4FC;background:{{ $mk ? '#E0E7FF' : '#EEF2FF' }}"
                                @if($isEditable) data-smt="{{ $smt }}" data-kategori="Pilihan" @endif
                                @if($mk) data-mk-name="{{ $mk->nama_mk }}" data-mk-sub="Smt {{ $smt }} · {{ $mk->sks_total }} SKS" @endif>
                                @if ($mk)
                                    <div class="px-2 py-1.5 select-none w-full h-full flex flex-col items-center justify-center gap-0.5
                                                hover:bg-indigo-200 transition-colors cursor-default">
                                        <span class="font-mono font-bold text-indigo-900 text-[11px] leading-none">{{ $mk->kode_mk }}</span>
                                        <span class="text-[9px] text-indigo-700 font-medium leading-none mt-0.5">{{ $mk->sks_total }} SKS</span>
                                    </div>
                                @elseif ($isEditable)
                                    <div class="flex items-center justify-center w-full h-full hover:bg-indigo-100 transition-colors">
                                        <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                        @endfor

                        {{-- ── MKWK ── --}}
                        @for ($i = 0; $i < $maxMkwk; $i++)
                            @php $mk = $mkwkMks[$i] ?? null; @endphp
                            <td class="border text-center align-middle p-0 {{ $isEditable ? 'mk-cell cursor-pointer' : '' }}"
                                style="min-width:88px;height:48px;border-color:#6EE7B7;background:{{ $mk ? '#D1FAE5' : '#ECFDF5' }}"
                                @if($isEditable) data-smt="{{ $smt }}" data-kategori="MKWK" @endif
                                @if($mk) data-mk-name="{{ $mk->nama_mk }}" data-mk-sub="Smt {{ $smt }} · {{ $mk->sks_total }} SKS" @endif>
                                @if ($mk)
                                    <div class="px-2 py-1.5 select-none w-full h-full flex flex-col items-center justify-center gap-0.5
                                                hover:bg-emerald-200 transition-colors cursor-default">
                                        <span class="font-mono font-bold text-emerald-900 text-[11px] leading-none">{{ $mk->kode_mk }}</span>
                                        <span class="text-[9px] text-emerald-700 font-medium leading-none mt-0.5">{{ $mk->sks_total }} SKS</span>
                                    </div>
                                @elseif ($isEditable)
                                    <div class="flex items-center justify-center w-full h-full hover:bg-emerald-100 transition-colors">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                        @endfor

                        {{-- ── MKDU ── --}}
                        @for ($i = 0; $i < $maxMkdu; $i++)
                            @php $mk = $mkduMks[$i] ?? null; @endphp
                            <td class="border text-center align-middle p-0 {{ $isEditable ? 'mk-cell cursor-pointer' : '' }}"
                                style="min-width:88px;height:48px;border-color:#FCD34D;background:{{ $mk ? '#FEF3C7' : '#FFFBEB' }}"
                                @if($isEditable) data-smt="{{ $smt }}" data-kategori="MKDU" @endif
                                @if($mk) data-mk-name="{{ $mk->nama_mk }}" data-mk-sub="Smt {{ $smt }} · {{ $mk->sks_total }} SKS" @endif>
                                @if ($mk)
                                    <div class="px-2 py-1.5 select-none w-full h-full flex flex-col items-center justify-center gap-0.5
                                                hover:bg-amber-200 transition-colors cursor-default">
                                        <span class="font-mono font-bold text-amber-900 text-[11px] leading-none">{{ $mk->kode_mk }}</span>
                                        <span class="text-[9px] text-amber-700 font-medium leading-none mt-0.5">{{ $mk->sks_total }} SKS</span>
                                    </div>
                                @elseif ($isEditable)
                                    <div class="flex items-center justify-center w-full h-full hover:bg-amber-100 transition-colors">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="px-3 py-2.5 text-center text-xs font-bold border border-slate-600"
                        style="background:#1E293B;color:#fff">Total</td>
                    <td class="px-3 py-2.5 text-center text-xs font-bold border border-slate-600"
                        style="background:#1E293B;color:#fff">{{ $totalSksAll }}</td>
                    <td class="px-3 py-2.5 text-center text-xs font-bold border border-slate-600"
                        style="background:#1E293B;color:#fff">{{ $mkList->count() }}</td>
                    <td colspan="{{ $maxWajib }}"
                        class="px-3 py-2 text-center text-xs font-bold border"
                        style="background:#2563EB;color:#fff;border-color:#1D4ED8">
                        {{ $mkList->where('kategori_mk','Wajib')->count() }} MK
                        · {{ $mkList->where('kategori_mk','Wajib')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS
                    </td>
                    <td colspan="{{ $maxPil }}"
                        class="px-3 py-2 text-center text-xs font-bold border"
                        style="background:#4F46E5;color:#fff;border-color:#3730A3">
                        {{ $mkList->where('kategori_mk','Pilihan')->count() }} MK
                        · {{ $mkList->where('kategori_mk','Pilihan')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS
                    </td>
                    <td colspan="{{ $maxMkwk }}"
                        class="px-3 py-2 text-center text-xs font-bold border"
                        style="background:#059669;color:#fff;border-color:#065F46">
                        {{ $mkList->where('kategori_mk','MKWK')->count() }} MK
                        · {{ $mkList->where('kategori_mk','MKWK')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS
                    </td>
                    <td colspan="{{ $maxMkdu }}"
                        class="px-3 py-2 text-center text-xs font-bold border"
                        style="background:#D97706;color:#fff;border-color:#B45309">
                        {{ $mkList->where('kategori_mk','MKDU')->count() }} MK
                        · {{ $mkList->where('kategori_mk','MKDU')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Peringatan MK belum dipetakan --}}
@if ($belumDipetakan->isNotEmpty())
<div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 text-sm text-amber-800 flex items-start gap-3">
    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <div>
        <p class="font-semibold">{{ $belumDipetakan->count() }} MK belum dipetakan ke kategori</p>
        <p class="text-xs mt-1.5 flex flex-wrap gap-1">
            @foreach ($belumDipetakan as $mk)
                <span class="font-mono bg-amber-100 border border-amber-200 px-1.5 py-0.5 rounded text-amber-900">
                    {{ $mk->kode_mk }} <span class="text-amber-600">(Smt {{ $mk->semester }})</span>
                </span>
            @endforeach
        </p>
        @if ($isEditable)
        <p class="text-xs mt-1.5 text-amber-600">Klik sel pada baris semester yang sesuai untuk memetakan MK di atas.</p>
        @endif
    </div>
</div>
@endif

{{-- ═══ POPUP PILIHAN MK ═══ --}}
@if ($isEditable)
<div id="mk-popup"
     class="bg-white rounded-xl shadow-2xl border border-gray-200"
     style="position:fixed;z-index:9999;width:300px;max-height:440px;display:none">

    <div class="px-4 py-3 border-b border-gray-100 flex items-start justify-between bg-slate-50 rounded-t-xl">
        <div class="min-w-0">
            <p class="text-xs font-bold text-gray-800 leading-tight" id="popup-title">Pilih MK</p>
            <p class="text-[11px] text-gray-500 mt-0.5" id="popup-subtitle"></p>
        </div>
        <button onclick="closePopup()"
                class="ml-2 shrink-0 text-gray-400 hover:text-gray-600 w-6 h-6 flex items-center justify-center rounded hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="px-3 py-2 border-b border-gray-100">
        <input type="text" id="popup-search" autocomplete="off"
               placeholder="Cari kode atau nama MK..."
               class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div id="popup-list" class="overflow-y-auto" style="max-height:300px"></div>

    <div id="popup-loading" class="hidden px-4 py-3 text-center text-xs text-gray-500">
        <svg class="w-4 h-4 animate-spin mx-auto mb-1 text-blue-500" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
        Menyimpan...
    </div>
</div>
@endif

{{-- Tooltip portal (position:fixed — tidak terpengaruh overflow container) --}}
<div id="mk-float-tooltip"
     style="position:fixed;z-index:9999;pointer-events:none;display:none;
            background:#1E293B;color:#fff;font-size:11px;font-weight:500;
            border-radius:8px;padding:7px 12px;box-shadow:0 4px 16px rgba(0,0,0,.18);
            white-space:nowrap;line-height:1.4;max-width:240px;text-align:center">
    <span id="mk-tooltip-name" style="display:block"></span>
    <span id="mk-tooltip-sub"  style="display:block;color:#94A3B8;font-size:10px;margin-top:2px"></span>
    <span id="mk-tooltip-arrow"
          style="position:absolute;left:50%;transform:translateX(-50%);top:100%;
                 border:5px solid transparent;border-top-color:#1E293B;border-bottom:0;
                 width:0;height:0"></span>
</div>

@endsection

@push('scripts')
<script>
// ─── Tooltip via position:fixed (tidak terclip overflow container) ─────────────
(function () {
    var tip      = document.getElementById('mk-float-tooltip');
    var tipName  = document.getElementById('mk-tooltip-name');
    var tipSub   = document.getElementById('mk-tooltip-sub');

    document.querySelectorAll('td[data-mk-name]').forEach(function (td) {
        td.addEventListener('mouseenter', function () {
            tipName.textContent = td.dataset.mkName;
            tipSub.textContent  = td.dataset.mkSub || '';
            tip.style.display   = 'block';
            positionTip(td);
        });
        td.addEventListener('mousemove', function () {
            positionTip(td);
        });
        td.addEventListener('mouseleave', function () {
            tip.style.display = 'none';
        });
    });

    function positionTip(el) {
        var rect = el.getBoundingClientRect();
        var tw   = tip.offsetWidth;
        var th   = tip.offsetHeight;
        var left = rect.left + rect.width / 2 - tw / 2;
        var top  = rect.top - th - 8;

        // Clamp to viewport
        if (left < 6) left = 6;
        if (left + tw > window.innerWidth - 6) left = window.innerWidth - tw - 6;
        if (top < 6) top = rect.bottom + 8; // flip below if not enough space above

        tip.style.left = left + 'px';
        tip.style.top  = top  + 'px';
    }
})();
</script>

@if ($isEditable)
@php
    $mkJsonData = $mkList->values()->map(fn($mk) => [
        'id'          => $mk->id,
        'kode_mk'     => $mk->kode_mk,
        'nama_mk'     => $mk->nama_mk,
        'semester'    => $mk->semester,
        'sks_total'   => $mk->sks_total ?? ($mk->sks_teori + $mk->sks_praktikum),
        'kategori_mk' => $mk->kategori_mk,
    ]);
@endphp
<script>
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
const UPDATE_URL = '{{ rtrim(route("kurikulum.organisasi-mk", $kurikulum), "/") }}/';
const allMks     = @json($mkJsonData);

const popup   = document.getElementById('mk-popup');
const pList   = document.getElementById('popup-list');
const pSearch = document.getElementById('popup-search');
const pLoad   = document.getElementById('popup-loading');

let popupSmt      = null;
let popupKategori = null;
let localMks      = allMks.map(m => ({...m}));

// ─── Cell click ───────────────────────────────────────────────────────────────
document.querySelectorAll('.mk-cell').forEach(function (cell) {
    cell.addEventListener('click', function (e) {
        e.stopPropagation();
        var smt      = parseInt(cell.dataset.smt);
        var kategori = cell.dataset.kategori;

        if (popup.style.display !== 'none'
            && popupSmt === smt && popupKategori === kategori) {
            closePopup(); return;
        }

        popupSmt      = smt;
        popupKategori = kategori;

        var romawi = ['','I','II','III','IV','V','VI','VII','VIII'];
        document.getElementById('popup-title').textContent =
            'Semester ' + romawi[smt] + ' — ' + kategori;

        var smtMks = localMks.filter(function(m) { return m.semester === smt; });
        document.getElementById('popup-subtitle').textContent =
            smtMks.length ? smtMks.length + ' MK di semester ini' : 'Tidak ada MK di semester ini';

        pSearch.value = '';
        renderList(smtMks, kategori);
        pSearch.oninput = function () { renderList(smtMks, kategori, this.value); };

        positionPopup(cell);
        popup.style.display = 'block';
        setTimeout(function() { pSearch.focus(); }, 60);
    });
});

document.addEventListener('click', function (e) {
    if (popup && !popup.contains(e.target)) closePopup();
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closePopup();
});

// ─── Render daftar MK ─────────────────────────────────────────────────────────
function renderList(mks, kategori, filter) {
    pList.innerHTML = '';
    var filtered = filter
        ? mks.filter(function(m) {
            return m.kode_mk.toLowerCase().includes(filter.toLowerCase())
                || m.nama_mk.toLowerCase().includes(filter.toLowerCase());
          })
        : mks;

    if (filtered.length === 0) {
        pList.innerHTML = '<p class="px-4 py-5 text-xs text-gray-400 text-center">Tidak ada MK yang cocok.</p>';
        return;
    }

    filtered.forEach(function (mk) {
        var isActive = mk.kategori_mk === kategori;
        var hasOther = mk.kategori_mk && !isActive;

        var item = document.createElement('div');
        item.className = 'px-4 py-2.5 cursor-pointer flex items-start gap-2.5 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0'
            + (isActive ? ' bg-blue-50' : '');

        var dot   = isActive
            ? '<span style="width:8px;height:8px;border-radius:50%;background:#3B82F6;flex-shrink:0;margin-top:4px;display:inline-block"></span>'
            : '<span style="width:8px;height:8px;border-radius:50%;background:#E2E8F0;flex-shrink:0;margin-top:4px;display:inline-block"></span>';

        var badge = hasOther
            ? '<span class="text-[9px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">' + mk.kategori_mk + '</span>'
            : (isActive ? '<span class="text-[9px] bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded">✓ aktif</span>' : '');

        var actionLabel = isActive
            ? '<span class="text-[10px] text-red-400 hover:text-red-600 shrink-0 font-medium self-center">Hapus</span>'
            : '<span class="text-[10px] text-blue-400 hover:text-blue-600 shrink-0 self-center">Pilih →</span>';

        item.innerHTML =
            dot +
            '<div class="flex-1 min-w-0">' +
                '<div class="flex items-center flex-wrap gap-1">' +
                    '<span class="font-mono font-bold text-xs ' + (isActive ? 'text-blue-700' : 'text-gray-800') + '">' + mk.kode_mk + '</span>' +
                    badge +
                '</div>' +
                '<p class="text-[10px] text-gray-600 mt-0.5">' + mk.nama_mk + '</p>' +
                '<p class="text-[10px] text-gray-400">' + mk.sks_total + ' SKS</p>' +
            '</div>' +
            actionLabel;

        item.addEventListener('click', function () {
            doUpdate(mk.id, isActive ? null : kategori);
        });

        pList.appendChild(item);
    });
}

// ─── AJAX update ──────────────────────────────────────────────────────────────
async function doUpdate(mkId, newKategori) {
    pList.style.opacity = '0.4';
    pLoad.classList.remove('hidden');
    pSearch.disabled = true;

    try {
        var resp = await fetch(UPDATE_URL + mkId, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ kategori_mk: newKategori }),
        });
        if (!resp.ok) throw new Error('HTTP ' + resp.status);
        var mk = localMks.find(function(m) { return m.id === mkId; });
        if (mk) mk.kategori_mk = newKategori;
        closePopup();
        window.location.reload();
    } catch (err) {
        pList.style.opacity = '1';
        pLoad.classList.add('hidden');
        pSearch.disabled = false;
        alert('Gagal menyimpan perubahan. Silakan coba lagi.');
    }
}

// ─── Posisi popup (position:fixed — viewport coords, no scrollY/X) ────────────
function positionPopup(cell) {
    var rect   = cell.getBoundingClientRect();
    var popW   = 300;
    var popH   = 440;
    var margin = 8;

    var top  = rect.bottom + margin;
    var left = rect.left;

    if (left + popW > window.innerWidth - margin)
        left = window.innerWidth - popW - margin;
    if (left < margin) left = margin;
    if (rect.bottom + popH > window.innerHeight - margin)
        top = rect.top - popH - margin;
    if (top < margin) top = margin;

    popup.style.top  = top  + 'px';
    popup.style.left = left + 'px';
}

// ─── Tutup popup ──────────────────────────────────────────────────────────────
function closePopup() {
    if (!popup) return;
    popup.style.display = 'none';
    pLoad.classList.add('hidden');
    pList.style.opacity = '1';
    pSearch.disabled = false;
    popupSmt = popupKategori = null;
}
</script>
@endif
@endpush
