@extends('layouts.app')

@section('title', 'Rekap Nilai per Mata Kuliah')
@section('header', 'Rekap Nilai per Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Rekap Nilai per MK</span>
@endsection

@section('content')

<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-4 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="flex-1">
        Pilih mata kuliah untuk melihat dan mengisi rekap nilai per komponen asesmen, dikelompokkan berdasarkan CPL &rarr; CPMK &rarr; Sub-CPMK,
        beserta capaian CPL mahasiswa. Hasil dapat diekspor ke Excel untuk dicetak.
    </p>
</div>

<form method="GET" class="flex items-center gap-2 mb-4">
    <label for="semester" class="text-xs font-medium text-gray-600">Semester Akademik:</label>
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
</form>

@if ($mkBySemester->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">Mata Kuliah harus diisi terlebih dahulu.</p>
        </div>
    </div>
@else

@include('layouts._search', ['target'=>'rekap-mk-wrap','placeholder'=>'Cari kode atau nama MK...','mode'=>'hide','rowSelector'=>'tbody tr'])

<div id="rekap-mk-wrap" class="rounded-xl border border-amber-200 shadow-sm overflow-hidden bg-white">
    <table class="w-full text-xs border-collapse">
        <thead>
            <tr style="background:#F59E0B">
                <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400 w-20">Semester</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400 w-32">Kode MK</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400">Nama Mata Kuliah</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-400 w-32">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-amber-50">
            @foreach ($mkBySemester as $smtNumber => $mkList)
                @foreach ($mkList->sortBy('kode_mk')->values() as $i => $mk)
                    <tr class="hover:bg-amber-50/40">
                        @if ($i === 0)
                            <td class="px-4 py-2.5 border border-amber-100 font-semibold text-amber-800 align-middle" rowspan="{{ $mkList->count() }}">
                                Smt {{ $smtNumber }}
                            </td>
                        @endif
                        <td class="px-4 py-2.5 border border-amber-100 font-mono font-bold text-blue-800">{{ $mk->kode_mk }}</td>
                        <td class="px-4 py-2.5 border border-amber-100 text-gray-700">{{ $mk->nama_mk }}</td>
                        <td class="px-4 py-2.5 border border-amber-100 text-center">
                            <a href="{{ route('kurikulum.penilaian.rekap-nilai.show', [$kurikulum, $mk, 'semester' => $semester?->id]) }}"
                               class="inline-flex items-center gap-1 text-xs bg-amber-100 text-amber-700 hover:bg-amber-200 font-medium px-3 py-1 rounded-full transition-colors">
                                Lihat Rekap
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>

@endif

@endsection
