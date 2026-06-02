@extends('layouts.app')
@section('title', 'Tambah CPL SN-Dikti')
@section('header', 'Tambah CPL SN-Dikti')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.cpl-sndikti.index', $kurikulum) }}" class="hover:text-blue-600">CPL SN-Dikti</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')
<form method="POST" action="{{ route('kurikulum.cpl-sndikti.store', $kurikulum) }}" class="max-w-2xl">
    @csrf
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-xs text-blue-800">
                <strong>Kode CPL SN-Dikti</strong> akan digenerate otomatis berdasarkan kategori yang dipilih
                (contoh: CPL-S01, CPL-KU05, CPL-KK03, CPL-P02).
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kategori') border-red-400 @enderror">
                    <option value="">— Pilih Kategori —</option>
                    @foreach ($kategoriOptions as $k)
                        <option value="{{ $k }}" {{ old('kategori') === $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
                @error('kategori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="4" required
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-400 @enderror"
                          placeholder="Deskripsi CPL SN-Dikti...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

        </div>
        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">Simpan</button>
            <a href="{{ route('kurikulum.cpl-sndikti.index', $kurikulum) }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">Batal</a>
        </div>
    </div>
</form>
@endsection
