@extends('layouts.app')
@section('title', 'Bobot Penilaian MK–CPL–CPMK')
@section('header', 'Bobot Penilaian MK–CPL–CPMK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Bobot Penilaian MK</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.penilaian.bobot-mk.export', $kurikulum)])
@endsection

@section('content')

@php
    $teknikMap = [
        'bobot_quiz'        => 'teknik_quiz',
        'bobot_observasi'   => 'teknik_observasi',
        'bobot_unjuk_kerja' => 'teknik_unjuk_kerja',
        'bobot_uts'         => 'teknik_uts',
        'bobot_uas'         => 'teknik_uas',
        'bobot_tes_lisan'   => 'teknik_tes_lisan',
    ];
@endphp

<div class="mb-4 flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
        Bobot (%) per CPMK per MK. Total bobot <strong>setiap MK = 100%</strong>.
        Kolom abu-abu = teknik tidak dipilih di
        <a href="{{ route('kurikulum.penilaian.teknik', $kurikulum) }}" class="underline font-medium hover:text-blue-900">Teknik Penilaian →</a>
    </div>
</div>

@if (!$kurikulum->isArsip())
<form method="POST" action="{{ route('kurikulum.penilaian.bobot-mk.save', $kurikulum) }}">
@csrf
@endif

@if (empty($tableData))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        Belum ada data CPMK. Silakan isi Mata Kuliah dan CPMK terlebih dahulu.
    </div>
@else

<div class="rounded-xl border border-blue-200 shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<table class="border-collapse text-xs">
    <thead>
        <tr style="background:#1D4ED8">
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">MK</th>
            <th class="px-4 py-3 text-left font-bold text-white border border-blue-400" style="min-width:160px">Nama MK</th>
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-16">CPL</th>
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-28">CPMK</th>
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">Partisipasi<br><span class="font-normal text-blue-200 text-[10px]">(Quiz)</span></th>
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">Observasi<br><span class="font-normal text-blue-200 text-[10px]">(Praktik/Tugas)</span></th>
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">Unjuk Kerja<br><span class="font-normal text-blue-200 text-[10px]">(Presentasi)</span></th>
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">Tes Tulis<br><span class="font-normal text-blue-200 text-[10px]">(UTS)</span></th>
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">Tes Tulis<br><span class="font-normal text-blue-200 text-[10px]">(UAS)</span></th>
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-20">Tes Lisan<br><span class="font-normal text-blue-200 text-[10px]">(Kelompok)</span></th>
            <th class="px-3 py-3 text-center font-bold text-white border border-blue-400 w-16">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tableData as $mkEntry)
            @php
                $mk         = $mkEntry['mk'];
                $cplRows    = $mkEntry['cpl_rows'];
                $rowCount   = $mkEntry['row_count'];
                $mkStart    = true;
                $mkRowBg    = '#EFF6FF';

                // Hitung total bobot semua CPMK di MK ini (hanya teknik aktif)
                $mkTotal = 0;
                foreach ($cplRows as $cr) {
                    foreach ($cr['cpmks'] as $c) {
                        $p = $c->penilaian;
                        if (!$p) continue;
                        $mkTotal += (float)($p->bobot_quiz ?? 0)
                                  + (float)($p->bobot_observasi ?? 0)
                                  + (float)($p->bobot_unjuk_kerja ?? 0)
                                  + (float)($p->bobot_uts ?? 0)
                                  + (float)($p->bobot_uas ?? 0)
                                  + (float)($p->bobot_tes_lisan ?? 0);
                    }
                }
            @endphp

            @foreach ($cplRows as $cplRow)
                @php
                    $cpl     = $cplRow['cpl'];
                    $cpmks   = $cplRow['cpmks'];
                    $cplStart = true;
                @endphp

                @foreach ($cpmks as $ci => $cpmk)
                    @php
                        $p     = $cpmk->penilaian;
                        $rowBg = ($ci % 2 === 0) ? '#EFF6FF' : '#FFFFFF';

                        $aktif = [
                            'bobot_quiz'        => $p && $p->teknik_quiz,
                            'bobot_observasi'   => $p && $p->teknik_observasi,
                            'bobot_unjuk_kerja' => $p && $p->teknik_unjuk_kerja,
                            'bobot_uts'         => $p && $p->teknik_uts,
                            'bobot_uas'         => $p && $p->teknik_uas,
                            'bobot_tes_lisan'   => $p && $p->teknik_tes_lisan,
                        ];

                        $rowTotal = 0;
                        foreach ($aktif as $f => $isAct) {
                            if ($isAct) $rowTotal += (float)($p->$f ?? 0);
                        }
                    @endphp
                    <tr class="bobot-mk-row" style="background:{{ $rowBg }}" data-cpmk="{{ $cpmk->id }}">

                        {{-- MK (rowspan) --}}
                        @if ($mkStart)
                            @php $mkStart = false; @endphp
                            <td class="px-3 py-2.5 text-center border border-blue-100 align-middle"
                                rowspan="{{ $rowCount }}"
                                style="background:#DBEAFE">
                                <span class="font-mono font-bold text-blue-900 text-[11px] block cursor-help"
                                      data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Smt {{ $mk->semester }}, {{ $mk->sks_total }} SKS)"
                                      data-tip-label="MK">{{ $mk->kode_mk }}</span>
                                <span class="text-[9px] text-blue-600">Smt {{ $mk->semester }}</span>
                            </td>
                            {{-- Nama MK (rowspan) --}}
                            <td class="px-4 py-2.5 border border-blue-100 align-middle text-[10px] text-gray-700"
                                rowspan="{{ $rowCount }}"
                                style="background:#DBEAFE;min-width:160px">
                                <div class="font-medium text-gray-800 leading-tight">{{ $mk->nama_mk }}</div>
                                <div class="text-[9px] text-gray-400 mt-0.5">{{ $mk->sks_total }} SKS
                                    @php $mkTot = round($mkTotal); @endphp
                                    · Total:
                                    <span class="{{ $mkTot == 100 ? 'text-emerald-600 font-bold' : ($mkTot > 0 ? 'text-amber-600' : 'text-gray-400') }}">
                                        {{ $mkTot > 0 ? $mkTot . '%' : '—' }}
                                    </span>
                                </div>
                            </td>
                        @endif

                        {{-- CPL (rowspan dalam grup MK) --}}
                        @if ($cplStart)
                            @php $cplStart = false; @endphp
                            <td class="px-3 py-2.5 text-center border border-blue-100 align-middle"
                                rowspan="{{ count($cpmks) }}"
                                style="background:#BFDBFE">
                                <span class="font-mono font-bold text-blue-900 text-[10px] cursor-help"
                                      data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                      data-tip-label="CPL">{{ $cpl->kode_cpl }}</span>
                            </td>
                        @endif

                        {{-- CPMK --}}
                        <td class="px-3 py-2.5 text-center border border-blue-100 align-middle">
                            <span class="font-mono font-bold text-[10px] bg-blue-100 text-blue-900 px-1.5 py-0.5 rounded cursor-help"
                                  data-tooltip="{{ $cpmk->kode_cpmk }}: {{ $cpmk->deskripsi }}"
                                  data-tip-label="CPMK">{{ $cpmk->kode_cpmk }}</span>
                        </td>

                        {{-- 6 bobot columns --}}
                        @foreach ([
                            'bobot_quiz'        => $p->bobot_quiz        ?? '',
                            'bobot_observasi'   => $p->bobot_observasi   ?? '',
                            'bobot_unjuk_kerja' => $p->bobot_unjuk_kerja ?? '',
                            'bobot_uts'         => $p->bobot_uts         ?? '',
                            'bobot_uas'         => $p->bobot_uas         ?? '',
                            'bobot_tes_lisan'   => $p->bobot_tes_lisan   ?? '',
                        ] as $field => $val)
                            @php $isActive = $aktif[$field] ?? false; @endphp
                            <td class="border border-blue-100 text-center align-middle px-1 py-1"
                                style="{{ !$isActive ? 'background:#F3F4F6' : '' }}">
                                @if (!$isActive)
                                    <span class="text-gray-300 text-base select-none">—</span>
                                    @if (!$kurikulum->isArsip())
                                        <input type="hidden" name="penilaian[{{ $cpmk->id }}][{{ $field }}]" value="">
                                    @endif
                                @elseif (!$kurikulum->isArsip())
                                    <input type="number"
                                           name="penilaian[{{ $cpmk->id }}][{{ $field }}]"
                                           value="{{ old("penilaian.{$cpmk->id}.{$field}", $val !== '' ? $val : '') }}"
                                           min="0" max="100" step="0.01"
                                           class="bobot-mk-input w-14 text-center border border-blue-300 rounded px-1 py-0.5 text-xs
                                                  focus:outline-none focus:ring-1 focus:ring-blue-400 bg-white"
                                           placeholder="0">
                                @else
                                    <span class="{{ $val !== '' ? 'font-medium text-gray-700' : 'text-gray-300' }}">
                                        {{ $val !== '' ? $val : '—' }}
                                    </span>
                                @endif
                            </td>
                        @endforeach

                        {{-- Total per CPMK --}}
                        <td class="border border-blue-100 text-center align-middle px-2">
                            @if (!$kurikulum->isArsip())
                                <span class="bobot-mk-total font-bold text-gray-400 text-xs">—</span>
                            @else
                                @php
                                    $totColor = $rowTotal == 100 ? 'text-emerald-700' : ($rowTotal > 0 ? 'text-amber-700' : 'text-gray-400');
                                @endphp
                                <span class="font-bold text-xs {{ $totColor }}">{{ $rowTotal > 0 ? $rowTotal : '—' }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background:#1D4ED8">
            <td colspan="11"
                class="px-5 py-2.5 text-right text-xs font-bold text-white border border-blue-400">
                JUMLAH TOTAL SETIAP MK = 100
            </td>
        </tr>
    </tfoot>
</table>
</div>
</div>

@if (!$kurikulum->isArsip())
<div class="mt-4 flex items-center gap-3">
    <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition-colors shadow-sm">
        Simpan Bobot Penilaian MK
    </button>
    <span class="text-xs text-gray-400">Total per MK seharusnya = 100%</span>
</div>
@endif

@endif

@if (!$kurikulum->isArsip())
</form>
@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
<script>
(function () {
    document.querySelectorAll('.bobot-mk-row').forEach(function (row) {
        var inputs  = row.querySelectorAll('.bobot-mk-input');
        var totalEl = row.querySelector('.bobot-mk-total');
        if (!totalEl) return;

        function calcTotal() {
            var sum = 0;
            inputs.forEach(function (inp) { sum += parseFloat(inp.value || 0); });
            sum = Math.round(sum * 100) / 100;
            totalEl.textContent = sum > 0 ? sum : '—';
            if (sum === 100) {
                totalEl.className = 'bobot-mk-total font-bold text-emerald-700 text-xs';
            } else if (sum > 0) {
                totalEl.className = 'bobot-mk-total font-bold text-amber-700 text-xs';
            } else {
                totalEl.className = 'bobot-mk-total font-bold text-gray-400 text-xs';
            }
        }

        inputs.forEach(function (inp) { inp.addEventListener('input', calcTotal); });
        calcTotal();
    });
})();
</script>
@endpush
