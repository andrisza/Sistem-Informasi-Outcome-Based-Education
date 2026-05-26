@extends('layouts.app')

@section('title', 'Edit Kurikulum')
@section('header', 'Edit Kurikulum')

@section('breadcrumb')
    <a href="{{ route('kurikulum.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Edit</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.update', $kurikulum) }}" class="max-w-3xl">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">

        {{-- Identitas --}}
        <div class="px-6 py-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Identitas Kurikulum</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kode Kurikulum <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $kurikulum->kode) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kode') border-red-400 @enderror">
                    @error('kode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Jenjang <span class="text-red-500">*</span></label>
                    <select name="jenjang"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenjang') border-red-400 @enderror">
                        <option value="">-- Pilih Jenjang --</option>
                        @foreach (['D3','D4','S1','S2','S3'] as $j)
                            <option value="{{ $j }}" {{ old('jenjang', $kurikulum->jenjang) === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jenjang') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Kurikulum <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kurikulum" value="{{ old('nama_kurikulum', $kurikulum->nama_kurikulum) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_kurikulum') border-red-400 @enderror">
                    @error('nama_kurikulum') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Program Studi <span class="text-red-500">*</span></label>
                    <input type="text" name="program_studi" value="{{ old('program_studi', $kurikulum->program_studi) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('program_studi') border-red-400 @enderror">
                    @error('program_studi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tahun Mulai <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun_mulai" value="{{ old('tahun_mulai', $kurikulum->tahun_mulai) }}"
                           min="2000" max="2100"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tahun_mulai') border-red-400 @enderror">
                    @error('tahun_mulai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tahun Selesai</label>
                    <input type="number" name="tahun_selesai" value="{{ old('tahun_selesai', $kurikulum->tahun_selesai) }}"
                           min="2000" max="2100"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tahun_selesai') border-red-400 @enderror"
                           placeholder="Kosongkan jika masih berlaku">
                    @error('tahun_selesai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- Visi Misi --}}
        <div class="px-6 py-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Visi, Misi, Tujuan & Sasaran</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Visi</label>
                    <textarea name="visi" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('visi', $kurikulum->visi) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Misi</label>
                    <textarea name="misi" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('misi', $kurikulum->misi) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tujuan</label>
                    <textarea name="tujuan" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('tujuan', $kurikulum->tujuan) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Sasaran</label>
                    <textarea name="sasaran" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('sasaran', $kurikulum->sasaran) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Perbarui Kurikulum
            </button>
            <a href="{{ route('kurikulum.show', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>

    </div>
</form>

@endsection
