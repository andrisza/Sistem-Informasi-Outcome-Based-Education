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

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-400 @enderror">{{ old('deskripsi', $pl->deskripsi) }}</textarea>
                @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                <select name="kategori"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Pilih Kategori —</option>
                    @if ($pl->kategori && !$kategoriOptions->contains($pl->kategori))
                        <option value="{{ $pl->kategori }}" selected>{{ $pl->kategori }} (lama)</option>
                    @endif
                    @foreach ($kategoriOptions as $k)
                        <option value="{{ $k }}" {{ old('kategori', $pl->kategori) == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Referensi</label>
                <input type="text" name="referensi" value="{{ old('referensi', $pl->referensi) }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="cth: IS2020, Permendikbudristek No.53/2023, SKKNI level 6">
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
