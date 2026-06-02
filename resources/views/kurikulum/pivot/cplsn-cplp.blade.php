@extends('layouts.app')

@section('title', 'Matriks CPL SN-Dikti ↔ CPL Prodi')
@section('header', 'Matriks CPL SN-Dikti ↔ CPL Prodi')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Matriks CPL-SN ↔ CPL-P</span>
@endsection

@section('content')

@if ($cplsnList->isEmpty() || $cplpList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">CPL SN-Dikti dan CPL Prodi harus tersedia terlebih dahulu sebelum mengisi matriks keterkaitan ini.</p>
        </div>
    </div>
@else

{{-- Legend (collapsible) --}}
<details class="mb-3 bg-white border border-gray-100 rounded-xl shadow-sm">
    <summary class="px-4 py-2 text-xs font-semibold text-gray-600 cursor-pointer hover:text-gray-900 select-none">
        Lihat deskripsi kode (CPL SN-Dikti &amp; CPL Prodi)
    </summary>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-4 py-3 border-t border-gray-100">
        <div>
            <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1.5">CPL SN-Dikti</p>
            <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                @foreach ($cplsnList as $csn)
                    <div class="flex items-start gap-2 text-xs">
                        <span class="font-mono font-bold text-amber-700 shrink-0 w-14">{{ $csn->kode }}</span>
                        <span class="text-gray-500 leading-snug">{{ Str::limit($csn->deskripsi ?? '', 80) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div>
            <p class="text-[10px] font-bold text-violet-700 uppercase tracking-wider mb-1.5">CPL Prodi</p>
            <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                @foreach ($cplpList as $cp)
                    <div class="flex items-start gap-2 text-xs">
                        <span class="font-mono font-bold text-violet-700 shrink-0 w-14">{{ $cp->kode_cpl }}</span>
                        <span class="text-gray-500 leading-snug">{{ Str::limit($cp->deskripsi ?? '', 80) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</details>

<div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
    <span class="flex items-center gap-1.5">
        <span class="inline-block w-5 h-5 rounded bg-amber-100 border border-amber-300 text-amber-700 font-bold text-sm text-center leading-5">✓</span>
        Terpetakan
    </span>
    <span class="flex items-center gap-1.5">
        <span class="inline-block w-5 h-5 rounded bg-white border border-gray-200 text-center leading-5"></span>
        Tidak terpetakan
    </span>
    <span class="text-emerald-600 font-medium">· Auto-save: setiap klik langsung tersimpan</span>
</div>

{{-- Validation warning --}}
<div id="mapping-warning" class="hidden mb-3 bg-amber-50 border border-amber-300 rounded-xl px-4 py-3 text-sm text-amber-800 flex items-start gap-2">
    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    <span id="mapping-warning-text"></span>
</div>

@include('layouts._search', ['target'=>'cplsn-wrap','placeholder'=>'Cari CPL SN-Dikti atau CPL Prodi...','mode'=>'dim','rowSelector'=>'tbody tr.pivot-row'])
<div id="pivot-form">

    <div id="cplsn-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="border-collapse" id="pivot-table">
                <thead>
                    <tr>
                        {{-- Corner --}}
                        <th style="background:#F59E0B;min-width:140px" class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400 sticky left-0 z-20">
                            CPL SN-Dikti \ CPL Prodi
                        </th>
                        {{-- CPL Prodi column headers --}}
                        @foreach ($cplpList as $cplp)
                            <th style="background:#F59E0B;min-width:68px" class="px-2 py-3 text-center text-xs font-bold text-white border border-amber-400">
                                <span class="font-mono cursor-help block"
                                      data-tooltip="{{ $cplp->kode_cpl }}: {{ $cplp->deskripsi ?? '' }}"
                                      data-tip-label="CPL Prodi">{{ $cplp->kode_cpl }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                @php
                    $katLabels = [
                        'Sikap'               => 'SIKAP (S)',
                        'Keterampilan Umum'   => 'KETERAMPILAN UMUM (KU)',
                        'Keterampilan Khusus' => 'KETERAMPILAN KHUSUS (KK)',
                        'Pengetahuan'         => 'PENGETAHUAN (P)',
                    ];
                    $katCounts = $cplsnList->groupBy('kategori')->map->count();
                @endphp
                <tbody>
                    @php $prevKat = null; $rowNo = 0; @endphp
                    @foreach ($cplsnList as $cplsn)
                        @php
                            $kat = $cplsn->kategori ?? 'Lainnya';
                            $rowNo++;
                        @endphp
                        {{-- Category group header --}}
                        @if ($kat !== $prevKat)
                            @php $prevKat = $kat; @endphp
                            <tr>
                                <td colspan="{{ $cplpList->count() + 1 }}"
                                    style="background:#FDE68A;color:#78350f"
                                    class="px-4 py-2 text-xs font-bold uppercase tracking-wider border border-amber-300 sticky left-0">
                                    {{ $katLabels[$kat] ?? strtoupper($kat) }}
                                    <span class="ml-1 font-normal opacity-70">({{ $katCounts[$kat] ?? 0 }} CPL)</span>
                                </td>
                            </tr>
                        @endif
                        {{-- Data row --}}
                        <tr class="pivot-row" style="{{ $rowNo % 2 === 0 ? 'background:#fffbeb' : 'background:#fff' }}">
                            <td style="min-width:160px;background:{{ $rowNo % 2 === 0 ? '#fef3c7' : '#fffbeb' }}"
                                class="px-4 py-2.5 border border-amber-200 sticky left-0 z-10">
                                <span class="font-mono font-bold text-amber-800 text-xs cursor-help"
                                      data-tooltip="{{ $cplsn->kode }} ({{ $cplsn->kategori }}): {{ $cplsn->deskripsi ?? '' }}"
                                      data-tip-label="CPL SN-Dikti">{{ $cplsn->kode }}</span>
                            </td>
                            @foreach ($cplpList as $cplp)
                                @php $checked = in_array($cplp->id, $existing[$cplsn->id] ?? []); @endphp
                                <td class="border border-amber-100 text-center align-middle pivot-cell {{ $checked ? 'is-checked' : '' }}"
                                    style="min-width:68px;height:42px"
                                    @unless ($kurikulum->isArsip())
                                        data-table="pivot_cplsn_cplp"
                                        data-keys='{"id_cpl_sndikti":{{ $cplsn->id }},"id_cpl_prodi":{{ $cplp->id }}}'
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
// Warning: CPL Prodi columns yang tidak dipetakan ke CPL SNDIKTI manapun
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
            warnText.textContent = unchecked.length + ' CPL Prodi tidak dipetakan ke CPL SNDIKTI manapun: ' + unchecked.join(', ');
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
