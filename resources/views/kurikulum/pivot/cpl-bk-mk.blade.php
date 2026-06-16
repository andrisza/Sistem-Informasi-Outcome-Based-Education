@extends('layouts.app')

@section('title', 'Matriks CPL ↔ BK ↔ MK')
@section('header', 'Pemetaan CPL ↔ Bahan Kajian ↔ Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Matriks CPL ↔ BK ↔ MK</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.pivot.cpl-bk-mk.export', $kurikulum)])
    @if (!$kurikulum->isArsip())
        <form method="POST" action="{{ route('kurikulum.pivot.cpl-bk-mk.sync', $kurikulum) }}">
            @csrf
            <button type="submit"
                    style="display:inline-flex;align-items:center;gap:6px;background:#2563EB;color:#fff;font-size:13px;font-weight:600;padding:7px 14px;border-radius:8px;border:none;cursor:pointer"
                    onclick="return confirm('Sinkronkan ulang matriks dari CPL↔BK dan MK↔BK?')">
                <svg style="width:15px;height:15px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Sync Ulang
            </button>
        </form>
    @endif
@endsection

@section('content')

@if ($cplList->isEmpty() || $bkList->isEmpty() || $mkById->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">CPL Prodi, Bahan Kajian, dan Mata Kuliah harus diisi terlebih dahulu.</p>
        </div>
    </div>
@else

{{-- Info banner: auto-derived --}}
<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-3 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div class="space-y-1">
        <p><strong>Matriks ini diisi otomatis</strong> — dihitung dari irisan <strong>CPL↔BK</strong> dan <strong>MK↔BK</strong> yang sudah dipetakan. Tidak perlu diisi manual.</p>
        <p class="text-blue-600">Jika data tidak sesuai, klik <strong>Sync Ulang</strong> di pojok kanan atas, atau perbarui pemetaan di <a href="{{ route('kurikulum.pivot.cpl-bk', $kurikulum) }}" class="underline">Matriks CPL↔BK</a> dan <a href="{{ route('kurikulum.pivot.mk-bk', $kurikulum) }}" class="underline">Matriks BK↔MK</a>.</p>
    </div>
</div>

{{-- Stats --}}
<div class="flex items-center gap-4 text-xs text-gray-500 mb-3 flex-wrap">
    <span><strong class="text-gray-700">{{ $totalRelasi }}</strong> total relasi</span>
    <span>·</span>
    <span><strong class="text-gray-700">{{ $bkTerisi }}/{{ $bkList->count() }}</strong> BK terpetakan</span>
    <span>·</span>
    <span><strong class="text-gray-700">{{ $mkById->count() }}</strong> MK tersedia</span>
</div>

@include('layouts._search', ['target'=>'cpl-bk-mk-wrap','placeholder'=>'Cari BK (kode/nama)...','mode'=>'dim','rowSelector'=>'tbody tr'])

<div id="cpl-bk-mk-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden bg-white">
    <div class="overflow-x-auto">
        <table class="border-collapse w-full text-xs">
            <thead>
                <tr>
                    <th style="background:#F59E0B;min-width:120px;width:120px"
                        class="px-3 py-3 text-left text-xs font-bold text-white border border-amber-400 sticky left-0 z-20">
                        BK \ CPL
                    </th>
                    @foreach ($cplList as $cpl)
                        <th style="background:#F59E0B;min-width:130px"
                            class="px-2 py-3 text-center text-xs font-bold text-white border border-amber-400">
                            <span class="font-mono cursor-help block"
                                  data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                  data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($bkList as $bk)
                    @php $rowMatriks = $matriks[$bk->id] ?? []; @endphp
                    <tr class="hover:bg-amber-50/30">
                        <td style="min-width:120px;width:120px;background:#FCD34D"
                            class="px-3 py-3 border border-amber-300 sticky left-0 z-10 align-middle">
                            <span class="font-mono font-bold text-amber-900 text-xs cursor-help block"
                                  data-tooltip="{{ $bk->kode_bk }}: {{ $bk->nama_bk }}"
                                  data-tip-label="Bahan Kajian">{{ $bk->kode_bk }}</span>
                            @if ($bk->nama_bk)
                                <span class="text-amber-700 text-[9px] block leading-tight mt-0.5 truncate max-w-[90px]"
                                      title="{{ $bk->nama_bk }}">{{ Str::limit($bk->nama_bk, 22) }}</span>
                            @endif
                        </td>
                        @foreach ($cplList as $cpl)
                            @php $mks = $rowMatriks[$cpl->id] ?? []; @endphp
                            <td class="border border-amber-100 align-top p-1.5 {{ count($mks) ? 'bg-amber-50/40' : 'bg-white' }}"
                                style="min-width:130px">
                                @if (count($mks))
                                    <div class="flex flex-col gap-1">
                                        @foreach ($mks as $mk)
                                            <span class="inline-flex items-center gap-1 bg-white border border-blue-200 rounded px-1.5 py-0.5 shadow-sm">
                                                <span class="font-mono text-[10px] font-semibold text-blue-700 cursor-help"
                                                      data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Smt {{ $mk->semester }}, {{ $mk->sks_total }} SKS)"
                                                      data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                                                <span class="text-[9px] text-slate-500 font-medium bg-slate-100 rounded px-1 leading-4">S{{ $mk->semester }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-200 text-[10px] select-none">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if ($totalRelasi === 0)
    <div class="mt-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-xs text-amber-800 flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <span>Belum ada relasi. Isi <a href="{{ route('kurikulum.pivot.cpl-bk', $kurikulum) }}" class="underline font-medium">Matriks CPL↔BK</a> dan <a href="{{ route('kurikulum.pivot.mk-bk', $kurikulum) }}" class="underline font-medium">Matriks BK↔MK</a> terlebih dahulu, lalu klik <strong>Sync Ulang</strong>.</span>
    </div>
@endif

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
