@extends('layouts.app')

@section('title', 'Edit PL ' . $pl->kode_pl)
@section('header', 'Edit Profil Lulusan')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.pl.index', $kurikulum) }}" class="hover:text-blue-600">Profil Lulusan</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $pl->kode_pl }}</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.pl.update', [$kurikulum, $pl]) }}" class="max-w-2xl">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kode PL <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_pl" value="{{ old('kode_pl', $pl->kode_pl) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kode_pl') border-red-400 @enderror">
                    @error('kode_pl') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $pl->urutan) }}" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('urutan') border-red-400 @enderror">
                    @error('urutan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-400 @enderror">{{ old('deskripsi', $pl->deskripsi) }}</textarea>
                @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori', $pl->kategori) }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ([
                    'ref_area_fungsi_1' => 'Ref. Area Fungsi 1',
                    'ref_area_fungsi_2' => 'Ref. Area Fungsi 2',
                    'ref_area_fungsi_3' => 'Ref. Area Fungsi 3'
                ] as $field => $label)
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">{{ $label }}</label>
                    <input type="text" name="{{ $field }}" value="{{ old($field, $pl->$field) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @endforeach
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Perbarui
            </button>
            <a href="{{ route('kurikulum.pl.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
