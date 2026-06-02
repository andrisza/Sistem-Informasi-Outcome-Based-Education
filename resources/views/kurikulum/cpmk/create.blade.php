@extends('layouts.app')

@section('title', 'Tambah CPMK')
@section('header', 'Tambah CPMK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.index', $kurikulum) }}" class="hover:text-blue-600">Mata Kuliah</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah]) }}" class="hover:text-blue-600">{{ $mataKuliah->kode_mk }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah CPMK</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.mata-kuliah.cpmk.store', [$kurikulum, $mataKuliah]) }}" class="max-w-2xl">
    @csrf

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">CPL Prodi Terkait <span class="text-red-500">*</span></label>
                <select name="id_cpl"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_cpl') border-red-400 @enderror">
                    <option value="">-- Pilih CPL --</option>
                    @foreach ($cplList as $cpl)
                        <option value="{{ $cpl->id }}" {{ old('id_cpl') == $cpl->id ? 'selected' : '' }}>
                            {{ $cpl->kode_cpl }} – {{ Str::limit($cpl->deskripsi, 60) }}
                        </option>
                    @endforeach
                </select>
                @error('id_cpl') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Level Bloom</label>
                <select name="level_bloom"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('level_bloom') border-red-400 @enderror">
                    <option value="">-- Tidak Ditentukan --</option>
                    <optgroup label="Kognitif (C)">
                        @foreach (['C1','C2','C3','C4','C5','C6'] as $lvl)
                            <option value="{{ $lvl }}" {{ old('level_bloom') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Afektif (A)">
                        @foreach (['A1','A2','A3','A4','A5'] as $lvl)
                            <option value="{{ $lvl }}" {{ old('level_bloom') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Psikomotorik (P)">
                        @foreach (['P1','P2','P3','P4','P5'] as $lvl)
                            <option value="{{ $lvl }}" {{ old('level_bloom') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                        @endforeach
                    </optgroup>
                </select>
                @error('level_bloom') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-400 @enderror"
                          placeholder="Deskripsi CPMK...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan
            </button>
            <a href="{{ route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah]) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
