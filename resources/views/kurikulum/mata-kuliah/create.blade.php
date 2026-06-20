@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah')
@section('header', 'Tambah Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.index', $kurikulum) }}" class="hover:text-blue-600">Mata Kuliah</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.mata-kuliah.store', $kurikulum) }}" class="max-w-2xl">
    @csrf

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Semester <span class="text-red-500">*</span></label>
                    <select name="semester"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('semester') border-red-400 @enderror">
                        <option value="">-- Pilih Semester --</option>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    @error('semester') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                <input type="text" name="nama_mk" value="{{ old('nama_mk') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_mk') border-red-400 @enderror"
                       placeholder="Nama mata kuliah">
                @error('nama_mk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">SKS Teori <span class="text-red-500">*</span></label>
                    <input type="number" name="sks_teori" value="{{ old('sks_teori', 2) }}" min="0" max="6"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sks_teori') border-red-400 @enderror">
                    @error('sks_teori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">SKS Praktikum <span class="text-red-500">*</span></label>
                    <input type="number" name="sks_praktikum" value="{{ old('sks_praktikum', 0) }}" min="0" max="6"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sks_praktikum') border-red-400 @enderror">
                    @error('sks_praktikum') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kompetensi MK</label>
                <select name="kompetensi_mk"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Utama"     {{ old('kompetensi_mk', 'Utama') === 'Utama'     ? 'selected' : '' }}>Utama</option>
                    <option value="Pendukung" {{ old('kompetensi_mk') === 'Pendukung' ? 'selected' : '' }}>Pendukung</option>
                </select>
                <p class="text-[10px] text-gray-400 mt-1">Utama = MK inti prodi, Pendukung = MK pilihan/pelengkap.</p>
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan
            </button>
            <a href="{{ route('kurikulum.mata-kuliah.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
