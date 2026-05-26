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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kode MK <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_mk" value="{{ old('kode_mk', $mataKuliah->kode_mk) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kode_mk') border-red-400 @enderror"
                           placeholder="cth: SI101">
                    @error('kode_mk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
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

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                <input type="text" name="nama_mk" value="{{ old('nama_mk', $mataKuliah->nama_mk) }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_mk') border-red-400 @enderror"
                       placeholder="Nama mata kuliah">
                @error('nama_mk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">SKS Teori <span class="text-red-500">*</span></label>
                    <input type="number" name="sks_teori" value="{{ old('sks_teori', $mataKuliah->sks_teori) }}" min="0" max="6"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sks_teori') border-red-400 @enderror">
                    @error('sks_teori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">SKS Praktikum <span class="text-red-500">*</span></label>
                    <input type="number" name="sks_praktikum" value="{{ old('sks_praktikum', $mataKuliah->sks_praktikum) }}" min="0" max="6"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sks_praktikum') border-red-400 @enderror">
                    @error('sks_praktikum') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kategori MK <span class="text-red-500">*</span></label>
                    <select name="kategori_mk"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kategori_mk') border-red-400 @enderror">
                        @foreach ($kategoriOptions as $kat)
                            <option value="{{ $kat }}" {{ old('kategori_mk', $mataKuliah->kategori_mk) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                    @error('kategori_mk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="rounded-lg bg-gray-50 px-4 py-3 text-xs text-gray-500">
                SKS Total = SKS Teori + SKS Praktikum (dihitung otomatis oleh sistem)
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
