@extends('layouts.app')

@section('title', 'Matriks CPL Prodi ↔ CPMK')
@section('header', 'Matriks CPL Prodi ↔ CPMK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Matriks CPL ↔ CPMK</span>
@endsection

@section('content')

@if ($cplList->isEmpty() || $cpmkList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">
                CPL Prodi dan CPMK harus tersedia terlebih dahulu.
                @if ($cpmkList->isEmpty())
                    Tambah CPMK lewat halaman <a href="{{ route('kurikulum.overview.cpmk', $kurikulum) }}" class="underline font-semibold">Peta CPMK</a>.
                @endif
            </p>
        </div>
    </div>
@else

{{-- Legend (collapsible) --}}
<details class="mb-3 bg-white border border-gray-100 rounded-xl shadow-sm">
    <summary class="px-4 py-2 text-xs font-semibold text-gray-600 cursor-pointer hover:text-gray-900 select-none">
        Lihat deskripsi kode (CPMK &amp; CPL Prodi)
    </summary>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-4 py-3 border-t border-gray-100">
        <div>
            <p class="text-[10px] font-bold text-blue-700 uppercase tracking-wider mb-1.5">CPMK</p>
            <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                @foreach ($cpmkList as $c)
                    <div class="flex items-start gap-2 text-xs">
                        <span class="font-mono font-bold text-blue-700 shrink-0 w-20">{{ $c->kode_cpmk }}</span>
                        <span class="text-gray-500 leading-snug">{{ \Str::limit($c->deskripsi, 70) }}
                            <span class="text-gray-400">({{ $c->mataKuliah->kode_mk ?? '' }})</span>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        <div>
            <p class="text-[10px] font-bold text-violet-700 uppercase tracking-wider mb-1.5">CPL Prodi</p>
            <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                @foreach ($cplList as $cpl)
                    <div class="flex items-start gap-2 text-xs">
                        <span class="font-mono font-bold text-violet-700 shrink-0 w-14">{{ $cpl->kode_cpl }}</span>
                        <span class="text-gray-500 leading-snug">{{ \Str::limit($cpl->deskripsi, 80) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</details>

<div class="flex items-center gap-3 text-xs text-gray-500 mb-3 flex-wrap">
    <span class="flex items-center gap-1.5">
        <span class="inline-block w-5 h-5 rounded bg-amber-100 border border-amber-300 text-amber-700 font-bold text-sm text-center leading-5">✓</span>
        Terpetakan (bobot default = 1.0)
    </span>
    <span class="text-emerald-600 font-medium">· Auto-save aktif</span>
    <span class="text-gray-400">· Hover kode untuk deskripsi · CPMK dikelompokkan per MK & Semester</span>
</div>

@include('layouts._search', ['target'=>'cpl-cpmk-wrap','placeholder'=>'Cari CPL atau CPMK...','mode'=>'dim','rowSelector'=>'tbody tr'])
<div id="pivot-form">
    <div id="cpl-cpmk-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="border-collapse">
                <thead>
                    <tr>
                        <th style="background:#F59E0B;min-width:200px" class="px-3 py-3 text-left text-xs font-bold text-white border border-amber-400 sticky left-0 z-20">
                            CPMK \ CPL
                        </th>
                        @foreach ($cplList as $cpl)
                            <th style="background:#F59E0B;min-width:72px" class="px-2 py-3 text-center text-xs font-bold text-white border border-amber-400">
                                <span class="font-mono cursor-help block"
                                      data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                      data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $byMk = $cpmkList->groupBy(fn ($c) => $c->id_mk);
                        $rowNo = 0;
                    @endphp
                    @foreach ($byMk as $mkId => $cpmks)
                        @php $mk = $cpmks->first()->mataKuliah; @endphp
                        {{-- MK group row --}}
                        <tr>
                            <td colspan="{{ $cplList->count() + 1 }}"
                                style="background:#DBEAFE;color:#1e3a8a"
                                class="px-3 py-2 text-xs font-bold border border-blue-200 sticky left-0">
                                <span class="text-[10px] uppercase text-blue-500 mr-2">Smt {{ $mk->semester }}</span>
                                <span class="font-mono">{{ $mk->kode_mk }}</span>
                                <span class="text-blue-700 font-normal ml-2">— {{ $mk->nama_mk }}</span>
                            </td>
                        </tr>
                        @foreach ($cpmks as $cpmk)
                            @php $rowNo++; @endphp
                            <tr style="background:{{ $rowNo % 2 === 0 ? '#fffbeb' : '#fff' }}" class="hover:bg-amber-50/40">
                                <td style="min-width:200px;background:{{ $rowNo % 2 === 0 ? '#eff6ff' : '#f8fafc' }}"
                                    class="px-3 py-2 border border-amber-200 sticky left-0 z-10">
                                    <span class="font-mono font-bold text-blue-800 text-xs cursor-help block"
                                          data-tooltip="{{ $cpmk->kode_cpmk }}: {{ $cpmk->deskripsi }}"
                                          data-tip-label="CPMK">{{ $cpmk->kode_cpmk }}</span>
                                    <span class="text-blue-400 text-[10px] block">{{ \Str::limit($cpmk->deskripsi, 38) }}</span>
                                </td>
                                @foreach ($cplList as $cpl)
                                    @php $checked = in_array($cpl->id, $existing[$cpmk->id] ?? []); @endphp
                                    <td class="border border-amber-100 text-center align-middle pivot-cell {{ $checked ? 'is-checked' : '' }}"
                                        style="min-width:72px;height:42px"
                                        @unless ($kurikulum->isArsip())
                                            data-table="pivot_cpl_cpmk"
                                            data-keys='{"id_cpl":{{ $cpl->id }},"id_cpmk":{{ $cpmk->id }}}'
                                        @endunless>
                                        <input type="checkbox"
                                               class="pivot-cb"
                                               {{ $checked ? 'checked' : '' }}
                                               {{ $kurikulum->isArsip() ? 'disabled' : '' }}>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-xs text-gray-500"><span id="pivot-changed-info"></span></div>
</div>

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@unless ($kurikulum->isArsip())
    @include('layouts._pivot-autosave')
@endunless
<script>
(function () {
    var info = document.getElementById('pivot-changed-info');
    function refresh() { if (info) info.textContent = document.querySelectorAll('.pivot-cb:checked').length + ' relasi aktif'; }
    document.querySelectorAll('.pivot-cell').forEach(c => c.addEventListener('click', () => setTimeout(refresh, 0)));
    refresh();
})();
</script>
@endpush
