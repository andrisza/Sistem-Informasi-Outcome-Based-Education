@extends('layouts.app')
@section('title', "Pertemuan Minggu ke-{$minggu}")
@section('header', "Pertemuan Minggu ke-{$minggu}")

@section('breadcrumb')
    <a href="{{ route('dosen.rps.index') }}" class="hover:text-blue-600">RPS Saya</a>
    <span class="mx-1">/</span>
    <a href="{{ route('dosen.rps.show', $rps) }}" class="hover:text-blue-600">Detail</a>
    <span class="mx-1">/</span>
    <a href="{{ route('dosen.rps.pertemuan.index', $rps) }}" class="hover:text-blue-600">Pertemuan</a>
    <span class="mx-1">/</span>
    <span class="text-gray-700 font-medium">Minggu {{ $minggu }}</span>
@endsection

@section('header-actions')
    @if ($minggu > 1)
        <a href="{{ route('dosen.rps.pertemuan.show', [$rps, $minggu - 1]) }}"
           class="inline-flex items-center gap-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm px-3 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg> Prev
        </a>
    @endif
    @if ($minggu < 16)
        <a href="{{ route('dosen.rps.pertemuan.show', [$rps, $minggu + 1]) }}"
           class="inline-flex items-center gap-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm px-3 py-2 rounded-lg transition-colors">
            Next <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    @endif
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-5 text-sm text-amber-800 font-medium">
        {{ $rps->mataKuliah->nama_mk ?? '—' }}
        <span class="font-normal text-amber-600">— {{ $rps->semester->nama ?? '' }}</span>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('dosen.rps.pertemuan.update', [$rps, $minggu]) }}" class="space-y-5">
            @csrf @method('PUT')

            {{-- Sub-CPMK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Sub-CPMK</label>
                <select name="id_sub_cpmk"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="">— Tidak ada / pilih nanti —</option>
                    @foreach ($subCpmkAll as $sc)
                        <option value="{{ $sc->id }}"
                            {{ old('id_sub_cpmk', $pertemuan?->id_sub_cpmk) == $sc->id ? 'selected' : '' }}>
                            {{ $sc->kode_sub_cpmk }} — {{ Str::limit($sc->deskripsi, 60) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Materi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Materi Pembelajaran <span class="text-red-500">*</span>
                </label>
                <textarea name="materi_pembelajaran" rows="3" required
                          placeholder="Pokok bahasan yang diajarkan pada pertemuan ini..."
                          class="w-full border {{ $errors->has('materi_pembelajaran') ? 'border-red-400' : 'border-gray-300' }} rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('materi_pembelajaran', $pertemuan?->materi_pembelajaran) }}</textarea>
                @error('materi_pembelajaran')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Indikator & Kriteria --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Indikator Penilaian</label>
                    <textarea name="indikator_penilaian" rows="3"
                              placeholder="Indikator ketercapaian..."
                              class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('indikator_penilaian', $pertemuan?->indikator_penilaian) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kriteria & Teknik Penilaian</label>
                    <textarea name="kriteria_teknik" rows="3"
                              placeholder="Tes tulis, presentasi, laporan..."
                              class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('kriteria_teknik', $pertemuan?->kriteria_teknik) }}</textarea>
                </div>
            </div>

            {{-- Bentuk Pembelajaran --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Bentuk Pembelajaran Luring</label>
                    <input type="text" name="bentuk_luring"
                           value="{{ old('bentuk_luring', $pertemuan?->bentuk_luring) }}"
                           placeholder="Kuliah tatap muka, praktikum..."
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Bentuk Pembelajaran Daring</label>
                    <input type="text" name="bentuk_daring"
                           value="{{ old('bentuk_daring', $pertemuan?->bentuk_daring) }}"
                           placeholder="LMS, video conference, e-learning..."
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
            </div>

            {{-- Bobot & Estimasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Bobot Penilaian (%)</label>
                    <input type="number" name="bobot_penilaian" min="0" max="100" step="0.5"
                           value="{{ old('bobot_penilaian', $pertemuan?->bobot_penilaian) }}"
                           placeholder="0"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Estimasi Waktu</label>
                    <input type="text" name="estimasi_waktu"
                           value="{{ old('estimasi_waktu', $pertemuan?->estimasi_waktu) }}"
                           placeholder="100 menit"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Media Pembelajaran</label>
                    <input type="text" name="media_pembelajaran"
                           value="{{ old('media_pembelajaran', $pertemuan?->media_pembelajaran) }}"
                           placeholder="Proyektor, laptop..."
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" name="action" value="save"
                        class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
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
