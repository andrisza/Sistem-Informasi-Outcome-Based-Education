@extends('layouts.app')

@section('title', 'Matriks MK ↔ CPL Prodi')
@section('header', 'Matriks Mata Kuliah ↔ CPL Prodi')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Matriks MK ↔ CPL</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.pivot.mk-cpl.export', $kurikulum)])
@endsection

@section('content')

@if ($mkList->isEmpty() || $cplList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">Mata Kuliah dan CPL Prodi harus diisi terlebih dahulu sebelum mengisi matriks ini.</p>
        </div>
    </div>
@else

{{-- Auto CPMK info banner --}}
<div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 mb-3 text-xs text-emerald-800">
    <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div class="space-y-0.5">
        <p><strong>CPMK & Sub-CPMK dibuat otomatis</strong> — setiap centang MK↔CPL baru yang dibuat akan otomatis membuat CPMK placeholder beserta 2 Sub-CPMK default.</p>
        <p class="text-emerald-600">Hasil auto-generate dapat dilihat dan disesuaikan di halaman <a href="{{ route('kurikulum.penilaian.mk-cpmk-subcpmk', $kurikulum) }}" class="underline font-medium">Peta MK–CPMK–Sub-CPMK</a>.</p>
    </div>
</div>

{{-- Legend (collapsible) --}}
<details class="mb-3 bg-white border border-gray-100 rounded-xl shadow-sm">
    <summary class="px-4 py-2 text-xs font-semibold text-gray-600 cursor-pointer hover:text-gray-900 select-none">
        Lihat deskripsi kode (Mata Kuliah &amp; CPL Prodi)
    </summary>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-4 py-3 border-t border-gray-100">
        <div>
            <p class="text-[10px] font-bold text-blue-700 uppercase tracking-wider mb-1.5">Mata Kuliah</p>
            <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                @foreach ($mkList as $mk)
                    <div class="flex items-start gap-2 text-xs">
                        <span class="font-mono font-bold text-blue-700 shrink-0 w-16">{{ $mk->kode_mk }}</span>
                        <span class="text-gray-500 leading-snug">{{ Str::limit($mk->nama_mk ?? '', 70) }} <span class="text-gray-400">S{{ $mk->semester }}</span></span>
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
                        <span class="text-gray-500 leading-snug">{{ Str::limit($cpl->deskripsi ?? '', 80) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</details>

<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-3 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="flex-1">
        Klik sel untuk toggle. <strong>Saat dicentang</strong>, sistem otomatis mencari CPMK yang sudah ada untuk (MK, CPL) — bila belum ada, CPMK + 2 Sub-CPMK placeholder akan dibuat otomatis dan pivot CPL↔CPMK ikut ter-update.
    </p>
</div>

<div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
    <span class="flex items-center gap-1.5">
        <span class="inline-block w-5 h-5 rounded bg-amber-100 border border-amber-300 text-amber-700 font-bold text-sm text-center leading-5">✓</span>
        Terpetakan
    </span>
    <span class="text-emerald-600 font-medium">· Auto-save aktif</span>
    <span class="text-gray-400">· Hover kode untuk deskripsi</span>
</div>

{{-- Validation warning --}}
<div id="mapping-warning" class="hidden mb-3 bg-amber-50 border border-amber-300 rounded-xl px-4 py-3 text-sm text-amber-800 flex items-start gap-2">
    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    <span id="mapping-warning-text"></span>
</div>

@include('layouts._search', ['target'=>'mk-cpl-wrap','placeholder'=>'Cari MK atau CPL...','mode'=>'dim','rowSelector'=>'tbody tr.pivot-row'])
<div id="pivot-form">

    <div id="mk-cpl-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="border-collapse" id="pivot-table">
                <thead>
                    <tr>
                        <th style="background:#F59E0B;min-width:240px" class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400 sticky left-0 z-20">
                            Mata Kuliah \ CPL
                        </th>
                        @foreach ($cplList as $cpl)
                            <th style="background:#F59E0B;min-width:72px" class="px-2 py-3 text-center text-xs font-bold text-white border border-amber-400">
                                <span class="font-mono cursor-help block"
                                      data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi ?? '' }}"
                                      data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php $sortedMk = $mkList->sortBy('kode_mk')->values(); @endphp
                    @foreach ($sortedMk as $rowNo => $mk)
                        <tr class="pivot-row" style="{{ $rowNo % 2 === 0 ? 'background:#fff' : 'background:#fffbeb' }}">
                            <td style="min-width:240px;background:{{ $rowNo % 2 === 0 ? '#dbeafe' : '#eff6ff' }}"
                                class="px-4 py-2.5 border border-amber-200 sticky left-0 z-10">
                                <span class="font-mono font-bold text-blue-800 text-xs cursor-help"
                                      data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Semester {{ $mk->semester }}, {{ $mk->sks_total }} SKS)"
                                      data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                                <span class="text-blue-600 text-xs ml-1.5">{{ $mk->nama_mk }}</span>
                            </td>
                            @foreach ($cplList as $cpl)
                                @php $checked = in_array($cpl->id, $existing[$mk->id] ?? []); @endphp
                                <td class="border border-amber-100 text-center align-middle pivot-cell {{ $checked ? 'is-checked' : '' }}"
                                    style="min-width:72px;height:42px"
                                    @unless ($kurikulum->isArsip())
                                        data-table="pivot_mk_cpl"
                                        data-keys='{"id_mk":{{ $mk->id }},"id_cpl":{{ $cpl->id }}}'
                                    @endunless>
                                    <input type="checkbox"
                                           class="pivot-cb"
                                           {{ $checked ? 'checked' : '' }}
                                           {{ $kurikulum->isArsip() ? 'disabled' : '' }}>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-xs text-gray-500">
        <span id="pivot-changed-info"></span>
    </div>
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
<script>
// Warning: CPL columns yang tidak dipetakan ke satu MK pun
(function () {
    var warning  = document.getElementById('mapping-warning');
    var warnText = document.getElementById('mapping-warning-text');
    if (!warning) return;
    function checkWarning() {
        var table = document.getElementById('pivot-table');
        if (!table) return;
        var headers = table.querySelectorAll('thead tr th');
        var unchecked = [];
        for (var colIdx = 1; colIdx < headers.length; colIdx++) {
            var cells = table.querySelectorAll('tbody tr td:nth-child(' + (colIdx + 1) + ') .pivot-cb');
            var anyChecked = Array.from(cells).some(function (cb) { return cb.checked; });
            if (cells.length > 0 && !anyChecked) {
                unchecked.push(headers[colIdx].textContent.trim().split('\n')[0].trim());
            }
        }
        if (unchecked.length > 0) {
            warnText.textContent = unchecked.length + ' CPL tidak dipetakan ke MK manapun: ' + unchecked.join(', ');
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    }
    document.querySelectorAll('.pivot-cb').forEach(function (cb) { cb.addEventListener('change', checkWarning); });
    checkWarning();
})();
</script>
@endpush


