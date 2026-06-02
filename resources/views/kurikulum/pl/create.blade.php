@extends('layouts.app')

@section('title', 'Tambah PL')
@section('header', 'Tambah Profil Lulusan')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.pl.index', $kurikulum) }}" class="hover:text-blue-600">Profil Lulusan</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.pl.store', $kurikulum) }}" class="max-w-2xl">
    @csrf

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-400 @enderror"
                          placeholder="Deskripsi profil lulusan...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                <select name="kategori"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Pilih Kategori —</option>
                    <option value="Kompetensi Utama"  {{ old('kategori') === 'Kompetensi Utama'  ? 'selected' : '' }}>Kompetensi Utama</option>
                    <option value="Kompetensi Sikap"  {{ old('kategori') === 'Kompetensi Sikap'  ? 'selected' : '' }}>Kompetensi Sikap</option>
                    <option value="Kompetensi Pendukung" {{ old('kategori') === 'Kompetensi Pendukung' ? 'selected' : '' }}>Kompetensi Pendukung</option>
                    @foreach ($kategoriOptions as $k)
                        @if (!in_array($k, ['Kompetensi Utama','Kompetensi Sikap','Kompetensi Pendukung']))
                            <option value="{{ $k }}" {{ old('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Referensi</label>
                <input type="text" name="referensi" value="{{ old('referensi') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="cth: IS2020, Permendikbudristek No.53/2023, SKKNI level 6">
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan
            </button>
            <a href="{{ route('kurikulum.pl.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
