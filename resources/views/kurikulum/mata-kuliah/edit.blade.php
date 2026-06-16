@extends('layouts.app')

@section('title', 'Edit MK – ' . $mataKuliah->kode_mk)
@section('header', 'Edit Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.index', $kurikulum) }}" class="hover:text-blue-600">Mata Kuliah</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $mataKuliah->kode_mk }}</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.mata-kuliah.update', [$kurikulum, $mataKuliah]) }}" class="max-w-2xl">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            {{-- Kode MK (readonly) --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kode MK</label>
                <div class="w-full border border-gray-200 rounded-lg px-3.5 py-2 text-sm bg-gray-50 text-gray-500 font-mono">
                    {{ $mataKuliah->kode_mk }}
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Kode MK ditetapkan otomatis dan tidak dapat diubah.</p>
            </div>

            {{-- Semester --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Semester <span class="text-red-500">*</span></label>
                    <select name="semester"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('semester') border-red-400 @enderror">
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('semester', $mataKuliah->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    @error('semester') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Nama MK --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                <input type="text" name="nama_mk" value="{{ old('nama_mk', $mataKuliah->nama_mk) }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_mk') border-red-400 @enderror"
                       placeholder="Nama mata kuliah">
                @error('nama_mk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- SKS --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">SKS <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <input type="number" name="sks_teori" value="{{ old('sks_teori', $mataKuliah->sks_teori) }}" min="0" max="6"
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sks_teori') border-red-400 @enderror"
                               placeholder="SKS Teori">
                        <p class="text-[11px] text-gray-400 mt-0.5">SKS Teori</p>
                        @error('sks_teori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="number" name="sks_praktikum" value="{{ old('sks_praktikum', $mataKuliah->sks_praktikum) }}" min="0" max="6"
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sks_praktikum') border-red-400 @enderror"
                               placeholder="SKS Praktikum">
                        <p class="text-[11px] text-gray-400 mt-0.5">SKS Praktikum</p>
                        @error('sks_praktikum') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Total = Teori + Praktikum (dihitung otomatis). Detail pembagian teori/praktikum dikelola di RPS.</p>
            </div>

            {{-- Kompetensi MK --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kompetensi MK</label>
                <select name="kompetensi_mk"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Utama"     {{ old('kompetensi_mk', $mataKuliah->kompetensi_mk ?? 'Utama') === 'Utama'     ? 'selected' : '' }}>Utama</option>
                    <option value="Pendukung" {{ old('kompetensi_mk', $mataKuliah->kompetensi_mk) === 'Pendukung' ? 'selected' : '' }}>Pendukung</option>
                </select>
            </div>

            {{-- Info: Kategori dikelola di Organisasi MK --}}
            <div class="rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 text-xs text-blue-700 flex items-start gap-2">
                <svg class="w-4 h-4 shrink-0 mt-0.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>
                    Kategori MK (Wajib / Pilihan / MKWK / MKDU) dikelola melalui
                    <a href="{{ route('kurikulum.organisasi-mk', $kurikulum) }}" class="underline font-medium">Organisasi MK</a>.
                </span>
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Perbarui
            </button>
            <a href="{{ route('kurikulum.mata-kuliah.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
