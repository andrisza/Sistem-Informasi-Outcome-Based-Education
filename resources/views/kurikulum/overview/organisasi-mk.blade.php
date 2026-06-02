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

@section('content')

@php
    // Semester dari tinggi ke rendah (VIII → I)
    $semesterList = range(8, 1);

    // Kelompok per semester dan per kategori
    $bySmt = $mkList->groupBy('semester');

    // Hitung max slot per kategori (kolom terbanyak yang dibutuhkan)
    $maxWajib = 0; $maxPil = 0; $maxMkwk = 0; $maxMkdu = 0;
    foreach ($semesterList as $smt) {
        $mks = $bySmt->get($smt, collect());
        $maxWajib = max($maxWajib, $mks->where('kategori_mk', 'Wajib')->count());
        $maxPil   = max($maxPil,   $mks->where('kategori_mk', 'Pilihan')->count());
        $maxMkwk  = max($maxMkwk,  $mks->where('kategori_mk', 'MKWK')->count());
        $maxMkdu  = max($maxMkdu,  $mks->where('kategori_mk', 'MKDU')->count());
    }
    // Minimal 1 kolom per kategori agar header tetap tampil
    $maxWajib = max($maxWajib, 1);
    $maxPil   = max($maxPil,   1);
    $maxMkwk  = max($maxMkwk,  1);
    $maxMkdu  = max($maxMkdu,  1);

    $totalCols = 3 + $maxWajib + $maxPil + $maxMkwk + $maxMkdu;

    // Statistik ringkas
    $totalSksAll = $mkList->sum(fn($m) => ($m->sks_teori ?? 0) + ($m->sks_praktikum ?? 0));
@endphp

{{-- Statistik ringkasan --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Total MK</p>
        <p class="text-2xl font-black text-gray-800 mt-0.5">{{ $mkList->count() }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Total SKS</p>
        <p class="text-2xl font-black text-amber-700 mt-0.5">{{ $totalSksAll }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">MK Wajib</p>
        <p class="text-2xl font-black text-yellow-700 mt-0.5">{{ $mkList->where('kategori_mk', 'Wajib')->count() }}</p>
        <p class="text-[10px] text-gray-400">{{ $mkList->where('kategori_mk', 'Wajib')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">MK Pilihan</p>
        <p class="text-2xl font-black text-violet-700 mt-0.5">{{ $mkList->where('kategori_mk', 'Pilihan')->count() }}</p>
        <p class="text-[10px] text-gray-400">{{ $mkList->where('kategori_mk', 'Pilihan')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS</p>
    </div>
</div>

{{-- Tabel organisasi MK --}}
<div class="rounded-xl border border-gray-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs" style="min-width: max-content; width: 100%">
            {{-- ═══ HEADER BARIS 1: Kategori ═══ --}}
            <thead>
                <tr>
                    <th colspan="3" style="background:#F59E0B;color:#fff;min-width:30px"
                        class="px-3 py-2 text-center text-xs font-bold border border-amber-400"></th>
                    <th colspan="{{ $maxWajib }}"
                        style="background:#FACC15;color:#713f12"
                        class="px-3 py-2 text-center text-xs font-bold border border-yellow-400 uppercase tracking-wide">
                        MK Wajib
                    </th>
                    <th colspan="{{ $maxPil }}"
                        style="background:#FDE047;color:#713f12"
                        class="px-3 py-2 text-center text-xs font-bold border border-yellow-400 uppercase tracking-wide">
                        MK-Pil
                    </th>
                    <th colspan="{{ $maxMkwk }}"
                        style="background:#FB923C;color:#fff"
                        class="px-3 py-2 text-center text-xs font-bold border border-orange-400 uppercase tracking-wide">
                        MKWK
                    </th>
                    <th colspan="{{ $maxMkdu }}"
                        style="background:#FDBA74;color:#7c2d12"
                        class="px-3 py-2 text-center text-xs font-bold border border-orange-300 uppercase tracking-wide">
                        MKDU
                    </th>
                </tr>
                {{-- ═══ HEADER BARIS 2: Smt/SKS/JmlMK --}}
                <tr style="background:#F59E0B">
                    <th class="px-3 py-2 text-center text-xs font-bold text-white border border-amber-400 w-12">Smt</th>
                    <th class="px-3 py-2 text-center text-xs font-bold text-white border border-amber-400 w-14">SKS</th>
                    <th class="px-3 py-2 text-center text-xs font-bold text-white border border-amber-400 w-14">Jml MK</th>
                    @for ($i = 0; $i < $maxWajib; $i++)
                        <th style="background:#FACC15;color:#713f12;min-width:68px" class="px-2 py-2 text-center border border-yellow-400"></th>
                    @endfor
                    @for ($i = 0; $i < $maxPil; $i++)
                        <th style="background:#FDE047;color:#713f12;min-width:68px" class="px-2 py-2 text-center border border-yellow-400"></th>
                    @endfor
                    @for ($i = 0; $i < $maxMkwk; $i++)
                        <th style="background:#FB923C;color:#fff;min-width:68px" class="px-2 py-2 text-center border border-orange-400"></th>
                    @endfor
                    @for ($i = 0; $i < $maxMkdu; $i++)
                        <th style="background:#FDBA74;color:#7c2d12;min-width:68px" class="px-2 py-2 text-center border border-orange-300"></th>
                    @endfor
                </tr>
            </thead>

            {{-- ═══ BODY: Satu baris per semester, VIII → I ═══ --}}
            <tbody>
                @foreach ($semesterList as $smt)
                    @php
                        $mks      = $bySmt->get($smt, collect());
                        $wajibMks = $mks->where('kategori_mk', 'Wajib')->values();
                        $pilMks   = $mks->where('kategori_mk', 'Pilihan')->values();
                        $mkwkMks  = $mks->where('kategori_mk', 'MKWK')->values();
                        $mkduMks  = $mks->where('kategori_mk', 'MKDU')->values();
                        $smtSks   = $mks->sum(fn($m) => $m->sks_teori + $m->sks_praktikum);
                        $jmlMk    = $mks->count();
                        $rowBg    = $smt % 2 === 0 ? '#fffbeb' : '#fff';
                    @endphp
                    <tr style="background:{{ $rowBg }}">
                        {{-- Smt --}}
                        <td class="px-3 py-2.5 text-center font-bold text-gray-700 border border-gray-200"
                            style="background:#F59E0B;color:#fff;min-width:48px">
                            @php
                                $romawi = ['', 'I','II','III','IV','V','VI','VII','VIII'];
                            @endphp
                            {{ $romawi[$smt] ?? $smt }}
                        </td>
                        {{-- SKS --}}
                        <td class="px-3 py-2.5 text-center font-semibold text-gray-700 border border-gray-200 w-14">
                            {{ $smtSks }}
                        </td>
                        {{-- Jml MK --}}
                        <td class="px-3 py-2.5 text-center border border-gray-200 w-14">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">{{ $jmlMk }}</span>
                        </td>

                        {{-- MK Wajib --}}
                        @for ($i = 0; $i < $maxWajib; $i++)
                            @php $mk = $wajibMks[$i] ?? null; @endphp
                            <td class="border border-yellow-300 text-center align-middle p-0" style="min-width:68px;height:38px;background:{{ $mk ? '#FACC15' : '#FFFDE7' }}">
                                @if ($mk)
                                    <div class="px-1 py-1 cursor-help"
                                         data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} | {{ $mk->sks_total }} SKS"
                                         data-tip-label="MK Wajib">
                                        <span class="font-mono font-bold text-yellow-900 text-xs">{{ $mk->kode_mk }}</span>
                                    </div>
                                @endif
                            </td>
                        @endfor

                        {{-- MK Pilihan --}}
                        @for ($i = 0; $i < $maxPil; $i++)
                            @php $mk = $pilMks[$i] ?? null; @endphp
                            <td class="border border-yellow-200 text-center align-middle p-0" style="min-width:68px;height:38px;background:{{ $mk ? '#FDE047' : '#FEFCE8' }}">
                                @if ($mk)
                                    <div class="px-1 py-1 cursor-help"
                                         data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} | {{ $mk->sks_total }} SKS"
                                         data-tip-label="MK Pilihan">
                                        <span class="font-mono font-bold text-yellow-800 text-xs">{{ $mk->kode_mk }}</span>
                                    </div>
                                @elseif ($jmlMk > 0 && $maxPil > 0)
                                    {{-- Placeholder kosong --}}
                                @endif
                            </td>
                        @endfor

                        {{-- MKWK --}}
                        @for ($i = 0; $i < $maxMkwk; $i++)
                            @php $mk = $mkwkMks[$i] ?? null; @endphp
                            <td class="border border-orange-300 text-center align-middle p-0" style="min-width:68px;height:38px;background:{{ $mk ? '#FB923C' : '#FFF7ED' }}">
                                @if ($mk)
                                    <div class="px-1 py-1 cursor-help"
                                         data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} | {{ $mk->sks_total }} SKS"
                                         data-tip-label="MKWK">
                                        <span class="font-mono font-bold text-white text-xs">{{ $mk->kode_mk }}</span>
                                    </div>
                                @endif
                            </td>
                        @endfor

                        {{-- MKDU --}}
                        @for ($i = 0; $i < $maxMkdu; $i++)
                            @php $mk = $mkduMks[$i] ?? null; @endphp
                            <td class="border border-orange-200 text-center align-middle p-0" style="min-width:68px;height:38px;background:{{ $mk ? '#FDBA74' : '#FFF7ED' }}">
                                @if ($mk)
                                    <div class="px-1 py-1 cursor-help"
                                         data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} | {{ $mk->sks_total }} SKS"
                                         data-tip-label="MKDU">
                                        <span class="font-mono font-bold text-orange-900 text-xs">{{ $mk->kode_mk }}</span>
                                    </div>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>

            {{-- ═══ FOOTER: Total ═══ --}}
            <tfoot>
                <tr style="background:#F59E0B">
                    <td class="px-3 py-2 text-center text-xs font-bold text-white border border-amber-400">Total</td>
                    <td class="px-3 py-2 text-center text-xs font-bold text-white border border-amber-400">{{ $totalSksAll }}</td>
                    <td class="px-3 py-2 text-center text-xs font-bold text-white border border-amber-400">{{ $mkList->count() }}</td>
                    <td colspan="{{ $maxWajib }}"
                        style="background:#FACC15;color:#713f12"
                        class="px-3 py-2 text-center text-xs font-bold border border-yellow-400">
                        {{ $mkList->where('kategori_mk','Wajib')->count() }} MK
                        · {{ $mkList->where('kategori_mk','Wajib')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS
                    </td>
                    <td colspan="{{ $maxPil }}"
                        style="background:#FDE047;color:#713f12"
                        class="px-3 py-2 text-center text-xs font-bold border border-yellow-400">
                        {{ $mkList->where('kategori_mk','Pilihan')->count() }} MK
                        · {{ $mkList->where('kategori_mk','Pilihan')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS
                    </td>
                    <td colspan="{{ $maxMkwk }}"
                        style="background:#FB923C;color:#fff"
                        class="px-3 py-2 text-center text-xs font-bold border border-orange-400">
                        {{ $mkList->where('kategori_mk','MKWK')->count() }} MK
                        · {{ $mkList->where('kategori_mk','MKWK')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS
                    </td>
                    <td colspan="{{ $maxMkdu }}"
                        style="background:#FDBA74;color:#7c2d12"
                        class="px-3 py-2 text-center text-xs font-bold border border-orange-300">
                        {{ $mkList->where('kategori_mk','MKDU')->count() }} MK
                        · {{ $mkList->where('kategori_mk','MKDU')->sum(fn($m) => $m->sks_teori + $m->sks_praktikum) }} SKS
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Legend --}}
<div class="mt-3 flex flex-wrap gap-2 text-xs items-center">
    <span class="text-gray-400 font-medium">Kategori:</span>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded font-medium" style="background:#FACC15;color:#713f12">
        <span class="w-2 h-2 rounded-sm" style="background:#FACC15;border:1px solid #ca8a04"></span>MK Wajib
    </span>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded font-medium" style="background:#FDE047;color:#713f12">
        <span class="w-2 h-2 rounded-sm" style="background:#FDE047;border:1px solid #ca8a04"></span>MK Pilihan
    </span>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded font-medium text-white" style="background:#FB923C">
        <span class="w-2 h-2 rounded-sm" style="background:#FB923C;border:1px solid #ea580c"></span>MKWK
    </span>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded font-medium" style="background:#FDBA74;color:#7c2d12">
        <span class="w-2 h-2 rounded-sm" style="background:#FDBA74;border:1px solid #f97316"></span>MKDU
    </span>
    <span class="text-gray-400 text-[10px] ml-2">· Hover kode MK untuk melihat nama dan SKS</span>
</div>

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
