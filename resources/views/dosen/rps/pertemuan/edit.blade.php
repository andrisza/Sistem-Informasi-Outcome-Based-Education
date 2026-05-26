@extends('layouts.app')

@section('title', "Pertemuan Minggu ke-{$minggu}")
@section('header', "Pertemuan Minggu ke-{$minggu}")

@section('breadcrumb')
    <a href="{{ route('dosen.rps.index') }}" class="hover:text-blue-600">RPS Saya</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('dosen.rps.show', $rps) }}" class="hover:text-blue-600">Detail</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('dosen.rps.pertemuan.index', $rps) }}" class="hover:text-blue-600">Pertemuan</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Minggu {{ $minggu }}</span>
@endsection

@section('header-actions')
    {{-- Navigasi minggu --}}
    @if ($minggu > 1)
        <a href="{{ route('dosen.rps.pertemuan.show', [$rps, $minggu - 1]) }}"
           class="inline-flex items-center gap-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm px-3 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Prev
        </a>
    @endif
    @if ($minggu < 16)
        <a href="{{ route('dosen.rps.pertemuan.show', [$rps, $minggu + 1]) }}"
           class="inline-flex items-center gap-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm px-3 py-2 rounded-lg transition-colors">
            Next
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    @endif
@endsection

@section('content')

<div class="max-w-2xl">
    {{-- Info RPS --}}
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 mb-6 flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-sm text-emerald-800 font-medium">
            {{ $rps->mataKuliah->nama_mk ?? '—' }}
            <span class="font-normal text-emerald-600">— {{ $rps->semester->nama ?? '' }}</span>
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('dosen.rps.pertemuan.update', [$rps, $minggu]) }}" class="space-y-5">
            @csrf @method('PUT')

            {{-- Materi Pembelajaran --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Materi Pembelajaran <span class="text-red-500">*</span>
                </label>
                <textarea name="materi_pembelajaran" rows="4" required
                          placeholder="Deskripsikan materi yang diajarkan pada pertemuan ini..."
                          class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                                 {{ $errors->has('materi_pembelajaran') ? 'border-red-400' : 'border-gray-300' }}">{{ old('materi_pembelajaran', $pertemuan?->materi_pembelajaran) }}</textarea>
                @error('materi_pembelajaran')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Metode Pembelajaran --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembelajaran</label>
                    <input type="text" name="metode_pembelajaran"
                           value="{{ old('metode_pembelajaran', $pertemuan?->metode_pembelajaran) }}"
                           placeholder="Ceramah, Diskusi, Case Study..."
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                {{-- Estimasi Waktu --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Estimasi Waktu</label>
                    <input type="text" name="estimasi_waktu"
                           value="{{ old('estimasi_waktu', $pertemuan?->estimasi_waktu) }}"
                           placeholder="100 menit"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            {{-- Indikator Penilaian --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Indikator Penilaian</label>
                <textarea name="indikator_penilaian" rows="3"
                          placeholder="Sebutkan indikator penilaian untuk pertemuan ini..."
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('indikator_penilaian', $pertemuan?->indikator_penilaian) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Media Pembelajaran --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Media Pembelajaran</label>
                    <input type="text" name="media_pembelajaran"
                           value="{{ old('media_pembelajaran', $pertemuan?->media_pembelajaran) }}"
                           placeholder="Proyektor, Laptop, Whiteboard..."
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            {{-- Referensi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Referensi</label>
                <textarea name="referensi" rows="3"
                          placeholder="Daftar referensi / literatur yang digunakan..."
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('referensi', $pertemuan?->referensi) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" name="action" value="save"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Simpan
                </button>
                @if ($minggu < 16)
                <button type="submit" name="action" value="next"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Simpan & Lanjut →
                </button>
                @endif
                <a href="{{ route('dosen.rps.pertemuan.index', $rps) }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Kembali</a>
            </div>
        </form>
    </div>
</div>

@endsection
