@extends('layouts.app')

@section('title', 'Upload Materi')
@section('header', 'Upload Materi Pembelajaran')

@section('breadcrumb')
    <a href="{{ route('dosen.materi.index') }}" class="hover:text-blue-600">Repositori Materi</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Upload</span>
@endsection

@section('content')

<form method="POST" action="{{ route('dosen.materi.store') }}" class="max-w-2xl">
    @csrf

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mata Kuliah <span class="text-red-500">*</span></label>
                    <select name="id_mk"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_mk') border-red-400 @enderror">
                        <option value="">-- Pilih MK --</option>
                        @foreach ($pengampuan as $p)
                            <option value="{{ $p->id_mk }}" {{ old('id_mk') == $p->id_mk ? 'selected' : '' }}>
                                {{ $p->mataKuliah->kode_mk }} – {{ $p->mataKuliah->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_mk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Semester <span class="text-red-500">*</span></label>
                    <select name="id_semester"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_semester') border-red-400 @enderror">
                        <option value="">-- Pilih Semester --</option>
                        @foreach ($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ old('id_semester') == $sem->id ? 'selected' : '' }}>
                                {{ $sem->nama_semester }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_semester') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama File / Judul Materi <span class="text-red-500">*</span></label>
                <input type="text" name="nama_file" value="{{ old('nama_file') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_file') border-red-400 @enderror"
                       placeholder="cth: Modul 1 – Pengantar OBE">
                @error('nama_file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis File <span class="text-red-500">*</span></label>
                <select name="jenis_file"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis_file') border-red-400 @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach ($jenisOptions as $j)
                        <option value="{{ $j }}" {{ old('jenis_file') == $j ? 'selected' : '' }}>{{ ucfirst($j) }}</option>
                    @endforeach
                </select>
                @error('jenis_file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Path / URL File <span class="text-red-500">*</span></label>
                <input type="text" name="file_path" value="{{ old('file_path') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('file_path') border-red-400 @enderror"
                       placeholder="https://drive.google.com/... atau path file">
                @error('file_path') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Keterangan singkat tentang materi ini...">{{ old('deskripsi') }}</textarea>
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan
            </button>
            <a href="{{ route('dosen.materi.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
