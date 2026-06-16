@extends('layouts.app')

@section('title', 'Assessment terhadap CPL dan MK')
@section('header', 'Assessment terhadap CPL dan MK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Assessment terhadap CPL dan MK</span>
@endsection

@section('header-actions')
    @if ($semester)
        @include('layouts._export-button', ['route' => route('kurikulum.penilaian.rekap-cpl.export', array_merge(['kurikulum' => $kurikulum, 'semester' => $semester->id], array_filter($filters)))])
    @endif
@endsection

@section('content')

@php
    $fmtNum = fn ($v) => $v == (int) $v ? (int) $v : $v;
@endphp

{{-- Selector Semester & Filter Mahasiswa --}}
<form method="GET" class="flex flex-wrap items-end gap-3 mb-4">
    <div>
        <label for="semester" class="block text-xs font-medium text-gray-600 mb-1">Semester Akademik:</label>
        <select name="semester" id="semester" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500">
            @forelse ($semesterList as $smt)
                <option value="{{ $smt->id }}" {{ $semester && $semester->id === $smt->id ? 'selected' : '' }}>
                    {{ $smt->nama }} {{ $smt->tahun_akademik }} {{ $smt->is_aktif ? '(Aktif)' : '' }}
                </option>
            @empty
                <option value="">— Belum ada semester —</option>
            @endforelse
        </select>
    </div>
    <div>
        <label for="tahun_masuk" class="block text-xs font-medium text-gray-600 mb-1">Tahun Angkatan:</label>
        <select name="tahun_masuk" id="tahun_masuk" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500">
            <option value="">Semua Angkatan</option>
            @foreach ($filterOptions['tahun_masuk'] as $tahun)
                <option value="{{ $tahun }}" {{ (string) ($filters['tahun_masuk'] ?? '') === (string) $tahun ? 'selected' : '' }}>
                    {{ $tahun }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="kelas" class="block text-xs font-medium text-gray-600 mb-1">Kelas:</label>
        <select name="kelas" id="kelas" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500">
            <option value="">Semua Kelas</option>
            @foreach ($filterOptions['kelas'] as $kelas)
                <option value="{{ $kelas }}" {{ ($filters['kelas'] ?? '') === $kelas ? 'selected' : '' }}>
                    {{ $kelas }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="program_studi" class="block text-xs font-medium text-gray-600 mb-1">Jurusan:</label>
        <select name="program_studi" id="program_studi" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500 max-w-xs">
            <option value="">Semua Jurusan</option>
            @foreach ($filterOptions['program_studi'] as $prodi)
                <option value="{{ $prodi }}" {{ ($filters['program_studi'] ?? '') === $prodi ? 'selected' : '' }}>
                    {{ $prodi }}
                </option>
            @endforeach
        </select>
    </div>
    @if (($filters['tahun_masuk'] ?? null) || ($filters['kelas'] ?? null) || ($filters['program_studi'] ?? null))
        <a href="{{ route('kurikulum.penilaian.rekap-cpl', [$kurikulum, 'semester' => $semester?->id]) }}"
           class="text-xs text-gray-500 hover:text-red-600 underline px-1 py-1.5">
            Reset filter
        </a>
    @endif
</form>

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
        Tabel ini merekap capaian CPL seluruh mahasiswa lintas Mata Kuliah dalam satu semester.
        Gunakan <strong>Export Excel</strong> untuk mengunduh tabel ini sebagai template, lalu unggah kembali file yang
        sudah diisi melalui <strong>Import Excel</strong> di bawah untuk memperbarui nilai setiap kolom CPMK. Kolom
        "Nilai Mata Kuliah", "Nilai CPL", dan "Capaian CPL" tidak perlu diisi karena dihitung ulang secara otomatis
        setelah import.
    </p>
</div>

@if (!$semester)
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        Belum ada Semester Akademik. Tambahkan semester terlebih dahulu.
    </div>
@elseif (empty($mkGroups) && empty($cplAggGroups))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Belum ada data</p>
            <p class="text-xs mt-0.5">Belum ada CPMK dengan Sub-CPMK &amp; Komponen Asesmen pada semester {{ $semester->nama }} {{ $semester->tahun_akademik }}.</p>
        </div>
    </div>
@else

{{-- Import form --}}
<div class="mb-4 bg-white border border-gray-100 rounded-xl shadow-sm px-4 py-3">
    <form method="POST" action="{{ route('kurikulum.penilaian.rekap-cpl.import', $kurikulum) }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
        @csrf
        <input type="hidden" name="semester" value="{{ $semester->id }}">
        <input type="hidden" name="tahun_masuk" value="{{ $filters['tahun_masuk'] }}">
        <input type="hidden" name="kelas" value="{{ $filters['kelas'] }}">
        <input type="hidden" name="program_studi" value="{{ $filters['program_studi'] }}">
        <label class="text-xs font-medium text-gray-600">Import dari Excel:</label>
        <input type="file" name="file" accept=".xlsx,.xls" required
               class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-1.5 rounded-lg transition-colors">
            Import Excel
        </button>
    </form>
</div>

@if ($mahasiswaList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        Belum ada mahasiswa yang terdaftar (enrollment aktif) pada mata kuliah-mata kuliah semester ini.
    </div>
@else

@include('kurikulum.penilaian._grid-cpl')

@endif
@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
