@extends('layouts.app')

@section('title', 'Edit CPL – ' . $cplProdi->kode_cpl)
@section('header', 'Edit CPL Prodi')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.cpl-prodi.index', $kurikulum) }}" class="hover:text-blue-600">CPL Prodi</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $cplProdi->kode_cpl }}</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.cpl-prodi.update', [$kurikulum, $cplProdi]) }}" class="max-w-2xl">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kategori') border-red-400 @enderror">
                    <option value="">— Pilih Kategori —</option>
                    @foreach ($kategoriOptions as $k)
                        <option value="{{ $k }}" {{ old('kategori', $cplProdi->kategori) == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                    {{-- If existing value is not in the list, show it anyway --}}
                    @if ($cplProdi->kategori && !$kategoriOptions->contains($cplProdi->kategori))
                        <option value="{{ $cplProdi->kategori }}" selected>{{ $cplProdi->kategori }}</option>
                    @endif
                </select>
                @error('kategori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="5"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-400 @enderror"
                          placeholder="Deskripsi CPL Prodi...">{{ old('deskripsi', $cplProdi->deskripsi) }}</textarea>
                @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Referensi</label>
                <textarea name="referensi" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="cth: IS2020 A3.1, SKKNI, IABEE...">{{ old('referensi', $cplProdi->referensi) }}</textarea>
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Perbarui
            </button>
            <a href="{{ route('kurikulum.cpl-prodi.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
