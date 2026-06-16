@extends('layouts.app')
@section('title', 'Bobot Penilaian CPMK')
@section('header', 'Bobot Penilaian CPMK')
@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Bobot Penilaian</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.penilaian.bobot.export', $kurikulum)])
@endsection

@section('content')

@php
    // Pemetaan bobot field → teknik field
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
        Isikan bobot (%) untuk setiap <strong>teknik yang aktif</strong> per CPMK. Total per CPMK sebaiknya <strong>100%</strong>.
        Kolom <span class="inline-block w-3 h-3 rounded bg-gray-200 border border-gray-300 align-middle mx-0.5"></span> abu-abu
        = teknik tidak dipilih di halaman <a href="{{ route('kurikulum.penilaian.teknik', $kurikulum) }}" class="underline font-medium hover:text-blue-900">Teknik Penilaian →</a>
    </div>
</div>

@if (!$kurikulum->isArsip())
<form method="POST" action="{{ route('kurikulum.penilaian.bobot.save', $kurikulum) }}">
@csrf
@endif

@if ($byCpl->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        Belum ada data CPMK. Silakan isi Mata Kuliah dan CPMK terlebih dahulu.
    </div>
@else
<div class="rounded-xl border border-emerald-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs">
            <thead>
                <tr style="background:#059669">
                    <th class="px-3 py-3 text-center font-bold text-white border border-emerald-400 w-16 sticky left-0 z-20">CPL</th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-emerald-400 w-20">MK</th>
                    <th class="px-4 py-3 text-left font-bold text-white border border-emerald-400" style="min-width:160px">Nama MK</th>
                    <th class="px-3 py-3 text-center font-bold text-white border border-emerald-400 w-28">CPMK</th>
                    @foreach ([
                        'bobot_quiz'        => ['Partisipasi',  'Quiz'],
                        'bobot_observasi'   => ['Observasi',    'Praktik/Tugas'],
                        'bobot_unjuk_kerja' => ['Unjuk Kerja',  'Presentasi'],
                        'bobot_uts'         => ['Tes Tulis',    'UTS'],
                        'bobot_uas'         => ['Tes Tulis',    'UAS'],
                        'bobot_tes_lisan'   => ['Tes Lisan',    'Kelompok'],
                    ] as $bField => $labels)
                        <th class="px-3 py-3 text-center font-bold text-white border border-emerald-400 w-20">
                            {{ $labels[0] }}<br>
                            <span class="font-normal text-emerald-100 text-[10px]">({{ $labels[1] }})</span>
                        </th>
                    @endforeach
                    <th class="px-3 py-3 text-center font-bold text-white border border-emerald-400 w-16">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cplList as $cpl)
                    @php $cpmks = $byCpl->get($cpl->id, collect()); @endphp
                    @if ($cpmks->isEmpty()) @continue @endif

                    @foreach ($cpmks as $ci => $cpmk)
                        @php
                            $p     = $cpmk->penilaian;
                            $rowBg = ($ci % 2 === 0) ? '#ecfdf5' : '#fff';

                            // Teknik yang aktif untuk CPMK ini
                            $aktif = [
                                'bobot_quiz'        => $p && $p->teknik_quiz,
                                'bobot_observasi'   => $p && $p->teknik_observasi,
                                'bobot_unjuk_kerja' => $p && $p->teknik_unjuk_kerja,
                                'bobot_uts'         => $p && $p->teknik_uts,
                                'bobot_uas'         => $p && $p->teknik_uas,
                                'bobot_tes_lisan'   => $p && $p->teknik_tes_lisan,
                            ];
                        @endphp
                        <tr class="bobot-row" style="background:{{ $rowBg }}" data-cpmk="{{ $cpmk->id }}">

                            {{-- CPL (rowspan) --}}
                            @if ($ci === 0)
                                <td class="px-3 py-2.5 text-center border border-emerald-100 align-middle sticky left-0 z-10"
                                    rowspan="{{ $cpmks->count() }}"
                                    style="background:#d1fae5">
                                    <span class="font-mono font-bold text-emerald-800 text-xs cursor-help"
                                          data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                          data-tip-label="CPL">{{ $cpl->kode_cpl }}</span>
                                </td>
                            @endif

                            {{-- MK --}}
                            <td class="px-3 py-2.5 text-center font-mono font-bold text-[10px] text-blue-700 border border-emerald-100">
                                {{ $cpmk->mataKuliah?->kode_mk ?? '—' }}
                            </td>

                            {{-- Nama MK --}}
                            <td class="px-4 py-2.5 text-gray-700 border border-emerald-100 text-[10px]">
                                {{ $cpmk->mataKuliah?->nama_mk ?? '—' }}
                            </td>

                            {{-- CPMK --}}
                            <td class="px-3 py-2.5 text-center border border-emerald-100">
                                <span class="font-mono font-bold text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded cursor-help"
                                      data-tooltip="{{ $cpmk->kode_cpmk }}: {{ $cpmk->deskripsi }}"
                                      data-tip-label="CPMK">{{ $cpmk->kode_cpmk }}</span>
                            </td>

                            {{-- 6 bobot columns — dinonaktifkan jika teknik tidak dipilih --}}
                            @foreach ([
                                'bobot_quiz'        => $p->bobot_quiz        ?? '',
                                'bobot_observasi'   => $p->bobot_observasi   ?? '',
                                'bobot_unjuk_kerja' => $p->bobot_unjuk_kerja ?? '',
                                'bobot_uts'         => $p->bobot_uts         ?? '',
                                'bobot_uas'         => $p->bobot_uas         ?? '',
                                'bobot_tes_lisan'   => $p->bobot_tes_lisan   ?? '',
                            ] as $field => $val)
                                @php $isActive = $aktif[$field] ?? false; @endphp
                                <td class="border border-emerald-100 text-center align-middle px-1 py-1"
                                    style="{{ !$isActive ? 'background:#F3F4F6' : '' }}">
                                    @if (!$isActive)
                                        {{-- Teknik tidak aktif — tampilkan sel abu-abu (non-editable) --}}
                                        <span class="text-gray-300 text-base select-none" title="Teknik ini tidak dipilih">—</span>
                                        @if (!$kurikulum->isArsip())
                                            {{-- Kirim nilai kosong agar tidak overwrite nilai lama --}}
                                            <input type="hidden"
                                                   name="penilaian[{{ $cpmk->id }}][{{ $field }}]"
                                                   value="">
                                        @endif
                                    @elseif (!$kurikulum->isArsip())
                                        <input type="number"
                                               name="penilaian[{{ $cpmk->id }}][{{ $field }}]"
                                               value="{{ old("penilaian.{$cpmk->id}.{$field}", $val !== '' ? $val : '') }}"
                                               min="0" max="100" step="0.01"
                                               class="bobot-input w-16 text-center border border-emerald-300 rounded px-1 py-0.5 text-xs
                                                      focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400
                                                      bg-white hover:border-emerald-400 transition-colors"
                                               placeholder="0">
                                    @else
                                        <span class="{{ $val !== '' ? 'text-gray-700 font-medium' : 'text-gray-400' }}">
                                            {{ $val !== '' ? $val : '—' }}
                                        </span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- Total --}}
                            <td class="border border-emerald-100 text-center align-middle px-2">
                                @if (!$kurikulum->isArsip())
                                    <span class="bobot-total font-bold text-gray-400">—</span>
                                @else
                                    @php
                                        $tot = collect($aktif)->keys()->sum(function($f) use ($p, $aktif) {
                                            return $aktif[$f] ? (float)($p->$f ?? 0) : 0;
                                        });
                                        $totColor = $tot == 100 ? 'text-emerald-700' : ($tot > 0 ? 'text-amber-700' : 'text-gray-400');
                                    @endphp
                                    <span class="font-bold {{ $totColor }}">{{ $tot > 0 ? $tot : '—' }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if (!$kurikulum->isArsip())
<div class="mt-4 flex items-center gap-3">
    <button type="submit"
            class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition-colors shadow-sm">
        Simpan Bobot Penilaian
    </button>
    <span class="text-xs text-gray-400">Total per CPMK seharusnya = 100%</span>
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
    document.querySelectorAll('.bobot-row').forEach(function (row) {
        var inputs  = row.querySelectorAll('.bobot-input');
        var totalEl = row.querySelector('.bobot-total');
        if (!totalEl) return;

        function calcTotal() {
            var sum = 0;
            inputs.forEach(function (inp) { sum += parseFloat(inp.value || 0); });
            sum = Math.round(sum * 100) / 100;
            totalEl.textContent = sum > 0 ? sum : '—';
            if (sum === 100) {
                totalEl.className = 'bobot-total font-bold text-emerald-700';
            } else if (sum > 0) {
                totalEl.className = 'bobot-total font-bold text-amber-700';
            } else {
                totalEl.className = 'bobot-total font-bold text-gray-400';
            }
        }

        inputs.forEach(function (inp) { inp.addEventListener('input', calcTotal); });
        calcTotal();
    });
})();
</script>
@endpush
