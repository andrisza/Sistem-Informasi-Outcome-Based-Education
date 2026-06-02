@extends('layouts.app')

@section('title', 'Tambah Bahan Kajian')
@section('header', 'Tambah Bahan Kajian')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.bahan-kajian.index', $kurikulum) }}" class="hover:text-blue-600">Bahan Kajian</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.bahan-kajian.store', $kurikulum) }}" class="max-w-2xl">
    @csrf

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Bahan Kajian <span class="text-red-500">*</span></label>
                <input type="text" name="nama_bk" value="{{ old('nama_bk') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_bk') border-red-400 @enderror"
                       placeholder="Nama bahan kajian">
                @error('nama_bk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Deskripsi singkat bahan kajian (opsional)...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kompetensi <span class="text-red-500">*</span></label>
                    <select name="kompetensi"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kompetensi') border-red-400 @enderror">
                        <option value="">— Pilih Kompetensi —</option>
                        <option value="Utama"     {{ old('kompetensi') === 'Utama'     ? 'selected' : '' }}>Utama</option>
                        <option value="Pendukung" {{ old('kompetensi') === 'Pendukung' ? 'selected' : '' }}>Pendukung</option>
                        <option value="Umum"      {{ old('kompetensi') === 'Umum'      ? 'selected' : '' }}>Umum</option>
                    </select>
                    @error('kompetensi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Referensi</label>
                    <input type="text" name="referensi" value="{{ old('referensi') }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="cth: IS2020, IABEE">
                </div>
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan
            </button>
            <a href="{{ route('kurikulum.bahan-kajian.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
