@extends('layouts.app')

@section('title', 'Detail Jurnal Mengajar')
@section('header', 'Detail Jurnal Mengajar')

@section('breadcrumb')
    <a href="{{ route('dosen.jurnal.index') }}" class="hover:text-blue-600">Jurnal Mengajar</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Detail</span>
@endsection

@section('header-actions')
    <a href="{{ route('dosen.jurnal.edit', $jurnal) }}"
       class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit
    </a>
@endsection

@section('content')

<div class="max-w-2xl space-y-5">

    {{-- Info utama --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="grid grid-cols-2 gap-5 mb-5">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Mata Kuliah</p>
                <p class="font-semibold text-gray-800">{{ $jurnal->pertemuan?->rps?->mataKuliah?->nama_mk ?? '—' }}</p>
                <p class="text-xs text-gray-400 font-mono">{{ $jurnal->pertemuan?->rps?->mataKuliah?->kode_mk ?? '' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Semester</p>
                <p class="font-semibold text-gray-800">{{ $jurnal->pertemuan?->rps?->semester?->nama ?? '—' }}</p>
                <p class="text-xs text-gray-400">{{ $jurnal->pertemuan?->rps?->semester?->tahun_akademik ?? '' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Pertemuan ke</p>
                <p class="font-semibold text-gray-800 text-2xl">{{ $jurnal->pertemuan?->minggu_ke ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Tanggal Pelaksanaan</p>
                <p class="font-semibold text-gray-800">{{ $jurnal->tanggal_pelaksanaan?->format('d M Y') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Jumlah Hadir</p>
                <p class="font-semibold text-gray-800 text-2xl text-emerald-600">{{ $jurnal->jumlah_hadir }}</p>
                <p class="text-xs text-gray-400">mahasiswa</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Dicatat pada</p>
                <p class="font-medium text-gray-700 text-sm">{{ $jurnal->created_at?->format('d M Y H:i') ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Realisasi Materi --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Realisasi Materi</h3>
        <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $jurnal->realisasi_materi }}</p>
    </div>

    {{-- Materi RPS (untuk perbandingan) --}}
    @if ($jurnal->pertemuan?->materi_pembelajaran)
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">Materi RPS (Rencana)</h3>
        <p class="text-sm text-gray-600 whitespace-pre-wrap leading-relaxed">{{ $jurnal->pertemuan->materi_pembelajaran }}</p>
    </div>
    @endif

    {{-- Catatan --}}
    @if ($jurnal->catatan)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Catatan</h3>
        <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $jurnal->catatan }}</p>
    </div>
    @endif

</div>

@endsection
