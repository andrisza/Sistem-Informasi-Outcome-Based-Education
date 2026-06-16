@extends('layouts.app')

@section('title', 'Matriks MK ↔ Bahan Kajian')
@section('header', 'Matriks Mata Kuliah ↔ Bahan Kajian')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Matriks MK ↔ BK</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.pivot.mk-bk.export', $kurikulum)])
@endsection

@section('content')

@if ($mkList->isEmpty() || $bkList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">Mata Kuliah dan Bahan Kajian harus diisi terlebih dahulu sebelum mengisi matriks ini.</p>
        </div>
    </div>
@else

{{-- Legend (collapsible) --}}
<details class="mb-3 bg-white border border-gray-100 rounded-xl shadow-sm">
    <summary class="px-4 py-2 text-xs font-semibold text-gray-600 cursor-pointer hover:text-gray-900 select-none">
        Lihat deskripsi kode (Mata Kuliah &amp; Bahan Kajian)
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
            <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider mb-1.5">Bahan Kajian</p>
            <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                @foreach ($bkList as $bk)
                    <div class="flex items-start gap-2 text-xs">
                        <span class="font-mono font-bold text-emerald-700 shrink-0 w-12">{{ $bk->kode_bk }}</span>
                        <span class="text-gray-500 leading-snug">{{ Str::limit($bk->nama_bk ?? '', 80) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</details>

<div class="flex items-center justify-between mb-3">
    <div class="flex items-center gap-3 text-xs text-gray-500">
        <span class="flex items-center gap-1.5">
            <span class="inline-block w-5 h-5 rounded bg-amber-100 border border-amber-300 text-amber-700 font-bold text-sm text-center leading-5">✓</span>
            Terpetakan
        </span>
        <span class="text-emerald-600 font-medium">· Auto-save aktif</span>
        <span class="text-violet-700 font-medium">· Perubahan otomatis menyinkronkan MK↔CPL & CPL↔BK↔MK</span>
    </div>
    @if (!$kurikulum->isArsip())
    <a href="{{ route('kurikulum.mata-kuliah.create', $kurikulum) }}"
       class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-sm">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah MK
    </a>
    @endif
</div>

{{-- Validation warning --}}
<div id="mapping-warning" class="hidden mb-3 bg-amber-50 border border-amber-300 rounded-xl px-4 py-3 text-sm text-amber-800 flex items-start gap-2">
    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    <span id="mapping-warning-text"></span>
</div>

@include('layouts._search', ['target'=>'mk-bk-wrap','placeholder'=>'Cari MK atau BK (kode/nama)...','mode'=>'dim','rowSelector'=>'tbody tr.pivot-row'])
<div id="pivot-form">

    <div id="mk-bk-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="border-collapse" id="pivot-table">
                <thead>
                    <tr>
                        <th style="background:#F59E0B;min-width:240px" class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400 sticky left-0 z-20">
                            Mata Kuliah \ BK
                        </th>
                        @foreach ($bkList as $bk)
                            <th style="background:#F59E0B;min-width:68px" class="px-2 py-3 text-center text-xs font-bold text-white border border-amber-400">
                                <span class="font-mono cursor-help block"
                                      data-tooltip="{{ $bk->kode_bk }}: {{ $bk->nama_bk }}{{ $bk->deskripsi ? ' — '.$bk->deskripsi : '' }}"
                                      data-tip-label="Bahan Kajian">{{ $bk->kode_bk }}</span>
                            </th>
                        @endforeach
                        @if (!$kurikulum->isArsip())
                            <th style="background:#F59E0B;min-width:80px" class="px-2 py-3 text-center text-xs font-bold text-white border border-amber-400">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sortedMk = $mkList->sortBy('kode_mk')->values();
                    @endphp
                    @foreach ($sortedMk as $rowNo => $mk)
                        <tr class="pivot-row" style="{{ $rowNo % 2 === 0 ? 'background:#fff' : 'background:#fffbeb' }}">
                            <td style="min-width:240px;background:{{ $rowNo % 2 === 0 ? '#dbeafe' : '#eff6ff' }}"
                                class="px-4 py-2.5 border border-amber-200 sticky left-0 z-10">
                                <span class="font-mono font-bold text-blue-800 text-xs cursor-help block"
                                      data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Semester {{ $mk->semester }}, {{ $mk->sks_total }} SKS)"
                                      data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                                <span class="text-blue-700 text-[10px] leading-tight block">{{ $mk->nama_mk }}</span>
                            </td>
                            @foreach ($bkList as $bk)
                                @php $checked = in_array($bk->id, $existing[$mk->id] ?? []); @endphp
                                <td class="border border-amber-100 text-center align-middle pivot-cell {{ $checked ? 'is-checked' : '' }}"
                                    style="min-width:68px;height:42px"
                                    @unless ($kurikulum->isArsip())
                                        data-table="pivot_mk_bk"
                                        data-keys='{"id_mk":{{ $mk->id }},"id_bk":{{ $bk->id }}}'
                                    @endunless>
                                    <input type="checkbox"
                                           class="pivot-cb"
                                           {{ $checked ? 'checked' : '' }}
                                           {{ $kurikulum->isArsip() ? 'disabled' : '' }}>
                                </td>
                            @endforeach
                            @if (!$kurikulum->isArsip())
                                <td class="border border-amber-100 text-center align-middle px-1" style="min-width:80px">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('kurikulum.mata-kuliah.edit', [$kurikulum, $mk]) }}"
                                           class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Edit MK">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('kurikulum.mata-kuliah.destroy', [$kurikulum, $mk]) }}"
                                              onsubmit="return confirm('Hapus MK {{ $mk->kode_mk }}? Semua CPMK dan pemetaan BK terkait juga akan dihapus.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus MK">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
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
<script>
// Warning: BK columns yang semua unchecked
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
            warnText.textContent = unchecked.length + ' BK tidak dipetakan ke MK manapun: ' + unchecked.join(', ');
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
