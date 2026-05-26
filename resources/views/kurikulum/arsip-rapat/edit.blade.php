@extends('layouts.app')

@section('title', 'Edit Arsip Rapat')
@section('header', 'Edit Arsip Rapat')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.arsip-rapat.index', $kurikulum) }}" class="hover:text-blue-600">Arsip Rapat</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Edit</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.arsip-rapat.update', [$kurikulum, $arsipRapat]) }}" class="max-w-2xl">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Judul Rapat <span class="text-red-500">*</span></label>
                <input type="text" name="judul_rapat" value="{{ old('judul_rapat', $arsipRapat->judul_rapat) }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('judul_rapat') border-red-400 @enderror">
                @error('judul_rapat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Rapat <span class="text-red-500">*</span></label>
                    <select name="jenis_rapat"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis_rapat') border-red-400 @enderror">
                        @foreach ($jenisOptions as $val => $label)
                            <option value="{{ $val }}" {{ old('jenis_rapat', $arsipRapat->jenis_rapat) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_rapat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Rapat <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_rapat" value="{{ old('tanggal_rapat', $arsipRapat->tanggal_rapat?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_rapat') border-red-400 @enderror">
                    @error('tanggal_rapat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Periode</label>
                    <select name="id_periode"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Periode --</option>
                        @foreach ($periodeList as $p)
                            <option value="{{ $p->id }}" {{ old('id_periode', $arsipRapat->id_periode) == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tempat</label>
                    <input type="text" name="tempat" value="{{ old('tempat', $arsipRapat->tempat) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Agenda</label>
                <textarea name="agenda" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('agenda', $arsipRapat->agenda) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Notulen</label>
                <textarea name="notulen" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notulen', $arsipRapat->notulen) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kesimpulan</label>
                <textarea name="kesimpulan" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('kesimpulan', $arsipRapat->kesimpulan) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('tindak_lanjut', $arsipRapat->tindak_lanjut) }}</textarea>
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Perbarui
            </button>
            <a href="{{ route('kurikulum.arsip-rapat.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
