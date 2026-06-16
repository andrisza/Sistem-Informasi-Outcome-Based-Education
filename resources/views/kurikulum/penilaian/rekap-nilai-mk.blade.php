@extends('layouts.app')

@section('title', 'Rekap Nilai – ' . $mataKuliah->kode_mk)
@section('header', 'Rekap Nilai – ' . $mataKuliah->nama_mk)

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.penilaian.rekap-nilai', $kurikulum) }}" class="hover:text-blue-600">Rekap Nilai per MK</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $mataKuliah->kode_mk }}</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.penilaian.rekap-nilai.export', [$kurikulum, $mataKuliah, 'semester' => $semester?->id])])
@endsection

@section('content')

@php
    $fmtNum = fn ($v) => $v == (int) $v ? (int) $v : $v;
@endphp

{{-- Selector MK & Semester --}}
<div class="flex flex-wrap items-center gap-3 mb-4">
    <div class="flex items-center gap-2">
        <label class="text-xs font-medium text-gray-600">Mata Kuliah:</label>
        <select onchange="if(this.value) window.location.href=this.value"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500">
            @foreach ($mkList as $mk)
                <option value="{{ route('kurikulum.penilaian.rekap-nilai.show', [$kurikulum, $mk, 'semester' => $semester?->id]) }}"
                        {{ $mk->id === $mataKuliah->id ? 'selected' : '' }}>
                    {{ $mk->kode_mk }} — {{ Str::limit($mk->nama_mk, 40) }} (Smt {{ $mk->semester }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex items-center gap-2">
        <label class="text-xs font-medium text-gray-600">Semester Akademik:</label>
        <select onchange="if(this.value) window.location.href=this.value"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500">
            @forelse ($semesterList as $smt)
                <option value="{{ route('kurikulum.penilaian.rekap-nilai.show', [$kurikulum, $mataKuliah, 'semester' => $smt->id]) }}"
                        {{ $semester && $semester->id === $smt->id ? 'selected' : '' }}>
                    {{ $smt->nama }} {{ $smt->tahun_akademik }} {{ $smt->is_aktif ? '(Aktif)' : '' }}
                </option>
            @empty
                <option value="">— Belum ada semester —</option>
            @endforelse
        </select>
    </div>
</div>

@if (session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-800">
        {{ session('error') }}
    </div>
@endif

{{-- Info banner --}}
<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-3 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="flex-1">
        Isi nilai mentah per komponen asesmen untuk setiap mahasiswa, lalu klik <strong>Simpan Nilai</strong>.
        Kolom <strong>Capaian CPL</strong> dan <strong>Nilai Mata Kuliah {{ $mataKuliah->kode_mk }}</strong> akan dihitung ulang secara otomatis
        (Sub-CPMK &rarr; CPMK &rarr; CPL) setelah data disimpan. Gunakan <strong>Export Excel</strong> untuk mengunduh template ini, diisi/dicetak secara manual,
        lalu unggah kembali via halaman Rekap Capaian CPL bila diperlukan.
    </p>
</div>

@if (!$semester)
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        Belum ada Semester Akademik. Tambahkan semester terlebih dahulu.
    </div>
@elseif (empty($cplGroups) && $lainnyaKomponen->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Belum ada komponen asesmen</p>
            <p class="text-xs mt-0.5">CPMK, Sub-CPMK, dan Komponen Asesmen untuk mata kuliah ini di semester {{ $semester->nama }} {{ $semester->tahun_akademik }} belum diisi.</p>
        </div>
    </div>
@elseif ($mahasiswaList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        Belum ada mahasiswa yang terdaftar (enrollment aktif) pada mata kuliah dan semester ini.
    </div>
@else

@php $isEditable = !$kurikulum->isArsip(); @endphp

<form method="POST" action="{{ route('kurikulum.penilaian.rekap-nilai.store', [$kurikulum, $mataKuliah]) }}">
    @csrf
    <input type="hidden" name="semester" value="{{ $semester->id }}">

    <div class="rounded-xl border border-amber-200 shadow-sm overflow-hidden bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse text-xs w-full">
                <thead>
                    <tr>
                        <th rowspan="4" style="background:#F59E0B;min-width:200px"
                            class="px-3 py-2 text-left text-xs font-bold text-white border border-amber-400 sticky left-0 z-20 align-middle">
                            Mahasiswa
                        </th>
                        @foreach ($cplGroups as $group)
                            <th colspan="{{ $group['total_cols'] }}" style="background:#F59E0B"
                                class="px-2 py-2 text-center text-xs font-bold text-white border border-amber-400">
                                <span class="font-mono cursor-help"
                                      data-tooltip="{{ $group['cpl']->kode_cpl }}: {{ $group['cpl']->deskripsi }}"
                                      data-tip-label="CPL Prodi">{{ $group['cpl']->kode_cpl }}</span>
                            </th>
                            <th rowspan="4" style="background:#F59E0B;min-width:90px"
                                class="px-2 py-2 text-center text-xs font-bold text-white border border-amber-400 align-middle">
                                Capaian<br>{{ $group['cpl']->kode_cpl }}
                            </th>
                        @endforeach
                        @if ($lainnyaKomponen->isNotEmpty())
                            <th rowspan="3" colspan="{{ $lainnyaKomponen->count() }}" style="background:#F59E0B"
                                class="px-2 py-2 text-center text-xs font-bold text-white border border-amber-400 align-middle">
                                Lainnya
                            </th>
                        @endif
                        @if ($mkTotalCol)
                            <th rowspan="4" style="background:#F59E0B;min-width:110px"
                                class="px-2 py-2 text-center text-xs font-bold text-white border border-amber-400 align-middle">
                                Nilai Mata Kuliah<br>{{ $mataKuliah->kode_mk }}<br>({{ $fmtNum($mkTotalCol['skor_maks']) }})
                            </th>
                        @endif
                    </tr>
                    <tr>
                        @foreach ($cplGroups as $group)
                            @foreach ($group['cpmk_groups'] as $cpmkGroup)
                                <th colspan="{{ $cpmkGroup['total_cols'] }}" style="background:#FCD34D"
                                    class="px-2 py-1.5 text-center text-xs font-bold text-amber-900 border border-amber-300">
                                    <span class="font-mono cursor-help"
                                          data-tooltip="{{ $cpmkGroup['cpmk']->kode_cpmk }}: {{ $cpmkGroup['cpmk']->deskripsi }}"
                                          data-tip-label="CPMK">{{ $cpmkGroup['cpmk']->kode_cpmk }}</span>
                                </th>
                            @endforeach
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($cplGroups as $group)
                            @foreach ($group['cpmk_groups'] as $cpmkGroup)
                                @foreach ($cpmkGroup['sub_groups'] as $subGroup)
                                    <th colspan="{{ $subGroup['komponen']->count() }}" style="background:#FEF3C7"
                                        class="px-2 py-1.5 text-center text-xs font-semibold text-amber-800 border border-amber-200">
                                        <span class="font-mono cursor-help"
                                              data-tooltip="{{ $subGroup['sub']->kode_sub_cpmk }}: {{ $subGroup['sub']->deskripsi }}"
                                              data-tip-label="Sub-CPMK">{{ $subGroup['sub']->kode_sub_cpmk }}</span>
                                    </th>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($cplGroups as $group)
                            @foreach ($group['cpmk_groups'] as $cpmkGroup)
                                @foreach ($cpmkGroup['sub_groups'] as $subGroup)
                                    @foreach ($subGroup['komponen'] as $k)
                                        <th style="background:#FFFBEB;min-width:90px"
                                            class="px-2 py-1.5 text-center text-[10px] font-semibold text-amber-700 border border-amber-100">
                                            {{ $k->nama_komponen }}
                                            <div class="text-amber-400 font-normal">maks {{ $k->skor_maks }}</div>
                                        </th>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                        @foreach ($lainnyaKomponen as $k)
                            <th style="background:#FFFBEB;min-width:90px"
                                class="px-2 py-1.5 text-center text-[10px] font-semibold text-amber-700 border border-amber-100">
                                {{ $k->nama_komponen }}
                                <div class="text-amber-400 font-normal">maks {{ $k->skor_maks }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mahasiswaList as $enrollment)
                        @php $mhs = $enrollment->mahasiswa; @endphp
                        <tr class="hover:bg-amber-50/30">
                            <td style="min-width:200px;background:#fffbeb"
                                class="px-3 py-2 border border-amber-100 sticky left-0 z-10 align-middle">
                                <p class="font-medium text-gray-800">{{ $mhs->name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $mhs->identifier ?? '-' }}</p>
                            </td>
                            @foreach ($columnLayout as $col)
                                @if ($col['type'] === 'komponen')
                                    @php
                                        $k = $col['komponen'];
                                        $val = $nilaiMap[$mhs->id][$k->id] ?? '';
                                    @endphp
                                    <td class="border border-amber-100 text-center p-1">
                                        <input type="number"
                                               name="nilai[{{ $mhs->id }}][{{ $k->id }}]"
                                               value="{{ $val }}"
                                               min="0" max="{{ $k->skor_maks }}" step="0.01"
                                               {{ $isEditable ? '' : 'disabled' }}
                                               class="w-20 text-center border border-gray-200 rounded-lg px-1.5 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500"
                                               placeholder="–">
                                    </td>
                                @elseif ($col['type'] === 'capaian')
                                    @php
                                        $cpl = $col['cpl'];
                                        $hasil = $hasilCplMap[$mhs->id][$cpl->id] ?? null;
                                    @endphp
                                    <td class="border border-amber-100 text-center align-middle p-1">
                                        @if ($hasil)
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $hasil->status_tercapai ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                {{ number_format((float) $hasil->nilai_cpl, 2) }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 text-[10px]">-</span>
                                        @endif
                                    </td>
                                @else
                                    @php $mkTotalVal = $mkTotalMap[$mhs->id] ?? null; @endphp
                                    <td style="background:#FFFBEB" class="border border-amber-100 text-center align-middle p-1 font-semibold text-amber-800">
                                        {{ $mkTotalVal !== null ? number_format($mkTotalVal, 2) : '-' }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($isEditable)
        <div class="mt-4">
            <button type="submit"
                    class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors shadow-sm">
                Simpan Nilai
            </button>
        </div>
    @endif
</form>

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
