@extends('layouts.app')
@section('title', 'Tahap & Mekanisme Penilaian')
@section('header', 'Tahap dan Mekanisme Penilaian')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tahap &amp; Mekanisme Penilaian</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.penilaian.tahap.export', $kurikulum)])
@endsection

@section('content')

<div class="flex items-center gap-3 mb-3 text-xs text-gray-500">
    <span>Teknik Penilaian diambil dari halaman
        <a href="{{ route('kurikulum.penilaian.teknik', $kurikulum) }}" class="text-blue-600 hover:underline font-medium">Teknik Penilaian →</a>
    </span>
</div>

@if (empty($tableData))
    <div class="bg-violet-50 border border-violet-200 rounded-xl px-5 py-5 text-sm text-violet-800">
        Belum ada data CPMK. Silakan isi Mata Kuliah dan CPMK terlebih dahulu.
    </div>
@else

@if (!$kurikulum->isArsip())
<form method="POST" action="{{ route('kurikulum.penilaian.tahap.save', $kurikulum) }}">
@csrf
@endif

<div class="rounded-xl border border-violet-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs w-full">
            <thead>
                <tr style="background:#7C3AED">
                    <th class="px-3 py-3 text-center text-white font-bold border border-violet-400 w-16 sticky left-0 z-20">CPL</th>
                    <th class="px-3 py-3 text-center text-white font-bold border border-violet-400 w-16">MK</th>
                    <th class="px-3 py-3 text-center text-white font-bold border border-violet-400 w-24">CPMK</th>
                    <th class="px-3 py-3 text-center text-white font-bold border border-violet-400 w-36">Tahap Penilaian</th>
                    <th class="px-4 py-3 text-center text-white font-bold border border-violet-400" style="min-width:240px">Teknik Penilaian</th>
                    <th class="px-4 py-3 text-center text-white font-bold border border-violet-400 w-36">Instrumen</th>
                    <th class="px-4 py-3 text-center text-white font-bold border border-violet-400" style="min-width:200px">Kriteria</th>
                    <th class="px-3 py-3 text-center text-white font-bold border border-violet-400 w-20">Bobot (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tableData as $entry)
                    @php
                        $cpl       = $entry['cpl'];
                        $mkRows    = $entry['mk_rows'];
                        $rowCount  = $entry['row_count'];
                        $cplStart  = true;
                    @endphp

                    @if (empty($mkRows))
                        <tr style="background:#f5f3ff">
                            <td class="px-3 py-2.5 text-center border border-violet-100 align-middle sticky left-0" style="background:#ede9fe">
                                <span class="font-mono font-bold text-violet-800 text-xs">{{ $cpl->kode_cpl }}</span>
                            </td>
                            <td colspan="7" class="px-4 py-2.5 text-gray-400 italic border border-violet-100">Belum ada CPMK</td>
                        </tr>
                    @else
                        @foreach ($mkRows as $mkEntry)
                            @php
                                $mk       = $mkEntry['mk'];
                                $cpmks    = $mkEntry['cpmks'];
                                $mkStart  = true;
                            @endphp

                            @foreach ($cpmks as $ci => $cpmk)
                                @php
                                    $p     = $cpmk->penilaian;
                                    $rowBg = ($ci % 2 === 0) ? '#f5f3ff' : '#fff';

                                    // Build teknik list from saved boolean fields
                                    $teknikList = [];
                                    if ($p) {
                                        if ($p->teknik_quiz)        $teknikList[] = 'Partisipasi (Quiz)';
                                        if ($p->teknik_observasi)   $teknikList[] = 'Observasi (Praktik / Tugas)';
                                        if ($p->teknik_unjuk_kerja) $teknikList[] = 'Unjuk Kerja (Presentasi)';
                                        if ($p->teknik_uts)         $teknikList[] = 'Tes Tulis (UTS)';
                                        if ($p->teknik_uas)         $teknikList[] = 'Tes Tulis (UAS)';
                                        if ($p->teknik_tes_lisan)   $teknikList[] = 'Tes Lisan (Tugas Kelompok)';
                                    }

                                    $totalBobot = (float)($p->bobot_quiz ?? 0)
                                                + (float)($p->bobot_observasi ?? 0)
                                                + (float)($p->bobot_unjuk_kerja ?? 0)
                                                + (float)($p->bobot_uts ?? 0)
                                                + (float)($p->bobot_uas ?? 0)
                                                + (float)($p->bobot_tes_lisan ?? 0);
                                @endphp

                                <tr style="background:{{ $rowBg }}" class="hover:bg-violet-50/30">

                                    {{-- CPL column (rowspan over all MK rows for this CPL) --}}
                                    @if ($cplStart)
                                        @php $cplStart = false; @endphp
                                        <td class="px-3 py-2.5 text-center border border-violet-200 align-middle sticky left-0 z-10"
                                            rowspan="{{ $rowCount }}"
                                            style="background:#ede9fe">
                                            <span class="font-mono font-bold text-violet-800 text-xs cursor-help"
                                                  data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                                  data-tip-label="CPL">{{ $cpl->kode_cpl }}</span>
                                        </td>
                                    @endif

                                    {{-- MK column (rowspan over all CPMKs for this MK under this CPL) --}}
                                    @if ($mkStart)
                                        @php $mkStart = false; @endphp
                                        <td class="px-3 py-2.5 text-center border border-violet-100 align-middle"
                                            rowspan="{{ count($cpmks) }}"
                                            style="background:#f3f0ff">
                                            <span class="font-mono font-bold text-blue-700 text-[10px] cursor-help block"
                                                  data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Smt {{ $mk->semester }})"
                                                  data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                                        </td>
                                    @endif

                                    {{-- CPMK --}}
                                    <td class="px-3 py-2.5 text-center border border-violet-100 align-middle">
                                        <span class="font-mono text-[10px] font-bold bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded cursor-help"
                                              data-tooltip="{{ $cpmk->kode_cpmk }}: {{ $cpmk->deskripsi }}"
                                              data-tip-label="CPMK">{{ $cpmk->kode_cpmk }}</span>
                                    </td>

                                    {{-- Tahap Penilaian (editable dropdown) --}}
                                    <td class="px-2 py-1.5 border border-violet-100 align-middle">
                                        @if (!$kurikulum->isArsip())
                                            <select name="penilaian[{{ $cpmk->id }}][tahap_penilaian]"
                                                    class="w-full border border-gray-300 rounded px-2 py-1 text-[10px] focus:outline-none focus:ring-1 focus:ring-violet-500 bg-white">
                                                <option value="Awal-Tengah Semester"
                                                    {{ ($p->tahap_penilaian ?? 'Awal-Tengah Semester') === 'Awal-Tengah Semester' ? 'selected' : '' }}>
                                                    Awal-Tengah Semester
                                                </option>
                                                <option value="Tengah-Akhir Semester"
                                                    {{ ($p->tahap_penilaian ?? '') === 'Tengah-Akhir Semester' ? 'selected' : '' }}>
                                                    Tengah-Akhir Semester
                                                </option>
                                                <option value="Awal-Akhir Semester"
                                                    {{ ($p->tahap_penilaian ?? '') === 'Awal-Akhir Semester' ? 'selected' : '' }}>
                                                    Awal-Akhir Semester
                                                </option>
                                            </select>
                                        @else
                                            <span class="text-gray-700 text-[10px]">{{ $p->tahap_penilaian ?? '—' }}</span>
                                        @endif
                                    </td>

                                    {{-- Teknik Penilaian (read-only dari Teknik Penilaian view) --}}
                                    <td class="px-3 py-2 border border-violet-100 align-top" style="min-width:240px">
                                        @if (!empty($teknikList))
                                            <span class="text-gray-700 text-[10px] leading-relaxed">{{ implode('; ', $teknikList) }}</span>
                                        @else
                                            <a href="{{ route('kurikulum.penilaian.teknik', $kurikulum) }}"
                                               class="text-[10px] text-violet-400 hover:text-violet-600 hover:underline italic">
                                                Belum dipilih — isi di Teknik Penilaian →
                                            </a>
                                        @endif
                                    </td>

                                    {{-- Instrumen (editable) --}}
                                    <td class="px-2 py-1.5 border border-violet-100 align-middle w-36">
                                        @if (!$kurikulum->isArsip())
                                            <input type="text"
                                                   name="penilaian[{{ $cpmk->id }}][instrumen]"
                                                   value="{{ old("penilaian.{$cpmk->id}.instrumen", $p->instrumen ?? '') }}"
                                                   class="w-full border border-gray-300 rounded px-2 py-1 text-[10px] focus:outline-none focus:ring-1 focus:ring-violet-500"
                                                   placeholder="cth: Rubrik holistik">
                                        @else
                                            <span class="text-gray-700 text-[10px]">{{ $p->instrumen ?? '—' }}</span>
                                        @endif
                                    </td>

                                    {{-- Kriteria (editable) --}}
                                    <td class="px-2 py-1.5 border border-violet-100 align-middle" style="min-width:200px">
                                        @if (!$kurikulum->isArsip())
                                            <input type="text"
                                                   name="penilaian[{{ $cpmk->id }}][kriteria]"
                                                   value="{{ old("penilaian.{$cpmk->id}.kriteria", $p->kriteria ?? '') }}"
                                                   class="w-full border border-gray-300 rounded px-2 py-1 text-[10px] focus:outline-none focus:ring-1 focus:ring-violet-500"
                                                   placeholder="cth: Skala penilaian sesuai dimensi CPMK">
                                        @else
                                            <span class="text-gray-700 text-[10px]">{{ $p->kriteria ?? '—' }}</span>
                                        @endif
                                    </td>

                                    {{-- Bobot (editable skor_maks) --}}
                                    <td class="px-2 py-1.5 text-center border border-violet-100 align-middle">
                                        @php
                                            $skorVal = $p && !is_null($p->skor_maks)
                                                ? (float)$p->skor_maks
                                                : ($totalBobot > 0 ? $totalBobot : '');
                                        @endphp
                                        @if (!$kurikulum->isArsip())
                                            <input type="number"
                                                   name="penilaian[{{ $cpmk->id }}][skor_maks]"
                                                   value="{{ old("penilaian.{$cpmk->id}.skor_maks", $skorVal) }}"
                                                   min="0" max="100" step="0.01"
                                                   class="w-14 text-center border border-violet-300 rounded px-1 py-0.5 text-xs
                                                          focus:outline-none focus:ring-1 focus:ring-violet-500 bg-white"
                                                   placeholder="0">
                                        @else
                                            @if ($skorVal !== '')
                                                <span class="font-bold text-amber-700 text-xs">{{ $skorVal }}</span>
                                            @else
                                                <span class="text-gray-300 text-xs">—</span>
                                            @endif
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if (!$kurikulum->isArsip())
    <div class="mt-4 flex items-center gap-3">
        <button type="submit"
                class="bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition-colors">
            Simpan
        </button>
        <span class="text-xs text-gray-400">
            Menyimpan: Tahap Penilaian, Instrumen, dan Kriteria.
            Teknik dikelola di <a href="{{ route('kurikulum.penilaian.teknik', $kurikulum) }}" class="text-violet-500 hover:underline">Teknik Penilaian</a>.
            Bobot dikelola di <a href="{{ route('kurikulum.penilaian.rumusan', $kurikulum) }}" class="text-violet-500 hover:underline">Rumusan Nilai Akhir MK</a>.
        </span>
    </div>
</form>
@endif

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
