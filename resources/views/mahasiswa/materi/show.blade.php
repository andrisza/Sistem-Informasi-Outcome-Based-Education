@extends('layouts.app')

@section('title', 'Detail Materi — ' . $materi->nama_file)
@section('header', 'Detail Materi')

@section('breadcrumb')
    <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('mahasiswa.materi.index') }}" class="hover:text-blue-600">Materi</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Detail</span>
@endsection

@section('content')

<div class="max-w-3xl space-y-5">

    {{-- Info Materi Utama --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start gap-4">
            {{-- File icon besar --}}
            @php
                $jenisColor = match(strtolower($materi->jenis_file ?? '')) {
                    'pdf'           => 'bg-red-100 text-red-600',
                    'pptx', 'ppt'   => 'bg-orange-100 text-orange-600',
                    'docx', 'doc'   => 'bg-blue-100 text-blue-600',
                    'xlsx', 'xls'   => 'bg-green-100 text-green-600',
                    'mp4', 'avi', 'mov' => 'bg-purple-100 text-purple-600',
                    'jpg', 'jpeg', 'png', 'gif' => 'bg-pink-100 text-pink-600',
                    'zip', 'rar'    => 'bg-yellow-100 text-yellow-600',
                    default         => 'bg-gray-100 text-gray-500',
                };
                $ukuran = $materi->ukuran_kb >= 1024
                    ? round($materi->ukuran_kb / 1024, 1) . ' MB'
                    : ($materi->ukuran_kb . ' KB');
            @endphp
            <div class="w-14 h-14 rounded-xl {{ $jenisColor }} flex items-center justify-center shrink-0 text-lg font-bold uppercase">
                {{ strtolower($materi->jenis_file ?? '?') }}
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-bold text-gray-900 break-words">{{ $materi->nama_file }}</h3>
                @if ($materi->deskripsi)
                    <p class="text-sm text-gray-600 mt-1">{{ $materi->deskripsi }}</p>
                @endif
                <div class="flex flex-wrap gap-3 mt-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        {{ $ukuran }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Diupload {{ $materi->created_at?->format('d M Y, H:i') ?? '-' }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $materi->dosen?->name ?? 'Tidak diketahui' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Tombol Download --}}
        <div class="mt-5 pt-5 border-t border-gray-100">
            @if ($materi->file_path)
                <a href="{{ Storage::url($materi->file_path) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download File
                </a>
            @else
                <p class="text-sm text-gray-400 italic">File tidak tersedia untuk diunduh.</p>
            @endif
        </div>
    </div>

    {{-- Info Mata Kuliah & Semester --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h4 class="text-sm font-semibold text-gray-800 mb-4">Informasi Mata Kuliah</h4>
        <dl class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Mata Kuliah</dt>
                <dd class="text-gray-800">{{ $materi->mataKuliah?->nama_mk ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Kode MK</dt>
                <dd class="text-gray-800 font-mono">{{ $materi->mataKuliah?->kode_mk ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Semester</dt>
                <dd class="text-gray-800">{{ $materi->semester?->nama ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">SKS</dt>
                <dd class="text-gray-800">{{ $materi->mataKuliah?->sks_total ?? '—' }}</dd>
            </div>
        </dl>
    </div>

    {{-- Info Pertemuan RPS (jika ada) --}}
    @if ($materi->pertemuan)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h4 class="text-sm font-semibold text-gray-800 mb-4">Terkait Pertemuan RPS</h4>
        <dl class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Pertemuan ke-</dt>
                <dd class="text-gray-800 font-semibold">{{ $materi->pertemuan->pertemuan_ke ?? '—' }}</dd>
            </div>
            @if ($materi->pertemuan->kemampuan_akhir ?? false)
            <div class="col-span-2">
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Kemampuan Akhir</dt>
                <dd class="text-gray-700 leading-relaxed">{{ $materi->pertemuan->kemampuan_akhir }}</dd>
            </div>
            @endif
            @if ($materi->pertemuan->metode_pembelajaran ?? false)
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Metode Pembelajaran</dt>
                <dd class="text-gray-700">{{ $materi->pertemuan->metode_pembelajaran }}</dd>
            </div>
            @endif
            @if ($materi->pertemuan->bahan_kajian ?? false)
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Bahan Kajian</dt>
                <dd class="text-gray-700">{{ $materi->pertemuan->bahan_kajian }}</dd>
            </div>
            @endif
        </dl>
    </div>
    @endif

    {{-- Navigasi kembali --}}
    <a href="{{ route('mahasiswa.materi.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 font-medium">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke daftar materi
    </a>

</div>

@endsection
