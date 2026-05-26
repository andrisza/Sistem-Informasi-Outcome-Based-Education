@extends('layouts.app')

@section('title', 'Edit Sub-CPMK – ' . $subCpmk->kode_sub_cpmk)
@section('header', 'Edit Sub-CPMK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah]) }}" class="hover:text-blue-600">{{ $mataKuliah->kode_mk }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk]) }}" class="hover:text-blue-600">{{ $cpmk->kode_cpmk }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $subCpmk->kode_sub_cpmk }}</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.update', [$kurikulum, $mataKuliah, $cpmk, $subCpmk]) }}" class="max-w-2xl">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kode Sub-CPMK <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_sub_cpmk" value="{{ old('kode_sub_cpmk', $subCpmk->kode_sub_cpmk) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kode_sub_cpmk') border-red-400 @enderror">
                    @error('kode_sub_cpmk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Urutan <span class="text-red-500">*</span></label>
                    <input type="number" name="urutan" value="{{ old('urutan', $subCpmk->urutan) }}" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('urutan') border-red-400 @enderror">
                    @error('urutan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-400 @enderror"
                          placeholder="Deskripsi sub-CPMK...">{{ old('deskripsi', $subCpmk->deskripsi) }}</textarea>
                @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Bobot (%) <span class="text-red-500">*</span></label>
                <input type="number" name="bobot" value="{{ old('bobot', $subCpmk->bobot) }}" min="0" max="100" step="0.1"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bobot') border-red-400 @enderror">
                @error('bobot') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Perbarui
            </button>
            <a href="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk]) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
