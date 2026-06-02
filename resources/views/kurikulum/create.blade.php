@extends('layouts.app')

@section('title', 'Tambah Kurikulum')
@section('header', 'Tambah Kurikulum')

@section('breadcrumb')
    <a href="{{ route('kurikulum.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.store') }}" class="max-w-2xl">
    @csrf

    {{-- Active semester info banner --}}
    @if ($activeSemester)
    <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-4 text-xs text-blue-800">
        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Semester Aktif: <strong>{{ $activeSemester->nama }}</strong> ({{ $activeSemester->tahun_akademik }})</span>
    </div>
    @else
    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-4 text-xs text-amber-800">
        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <span>Tidak ada semester akademik yang aktif. Pastikan semester aktif sudah diatur di menu Semester Akademik.</span>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">

        {{-- Section 1: Identitas Kurikulum --}}
        <div class="px-6 py-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Identitas Kurikulum</h3>

            {{-- Program Studi full width --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Program Studi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="program_studi" id="program_studi"
                       value="{{ old('program_studi') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('program_studi') border-red-400 @enderror"
                       placeholder="cth: Sistem Informasi">
                @error('program_studi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Row: Jenjang + Tahun Mulai + Tahun Selesai --}}
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Jenjang <span class="text-red-500">*</span>
                    </label>
                    <select name="jenjang" id="jenjang"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenjang') border-red-400 @enderror">
                        <option value="">-- Pilih --</option>
                        @foreach (['D3','D4','S1','S2','S3'] as $j)
                            <option value="{{ $j }}" {{ old('jenjang') === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jenjang') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Tahun Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="tahun_mulai" id="tahun_mulai"
                           value="{{ old('tahun_mulai', $activeSemester ? (int) substr($activeSemester->tahun_akademik, 0, 4) : $currentYear) }}"
                           min="2000" max="2100"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tahun_mulai') border-red-400 @enderror">
                    @error('tahun_mulai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tahun Selesai</label>
                    <input type="number" name="tahun_selesai" value="{{ old('tahun_selesai') }}"
                           min="2000" max="2100"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tahun_selesai') border-red-400 @enderror"
                           placeholder="Opsional">
                    @error('tahun_selesai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Nama Kurikulum --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Nama Kurikulum <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_kurikulum" value="{{ old('nama_kurikulum') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_kurikulum') border-red-400 @enderror"
                       placeholder="cth: Kurikulum Program Studi Sistem Informasi 2025">
                @error('nama_kurikulum') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 2: Visi Misi (collapsible) --}}
        <details class="px-6 py-5">
            <summary class="text-sm font-semibold text-gray-800 cursor-pointer select-none hover:text-blue-700">
                Visi, Misi, Tujuan &amp; Sasaran
                <span class="text-xs font-normal text-gray-400 ml-1">(opsional, dapat diisi nanti)</span>
            </summary>
            <div class="space-y-4 mt-4">
                @foreach ([
                    ['visi',    'Visi',    'Visi program studi...'],
                    ['misi',    'Misi',    'Misi program studi...'],
                    ['tujuan',  'Tujuan',  'Tujuan program studi...'],
                    ['sasaran', 'Sasaran', 'Sasaran program studi...'],
                ] as [$field, $label, $ph])
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">{{ $label }}</label>
                    <textarea name="{{ $field }}" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="{{ $ph }}">{{ old($field) }}</textarea>
                </div>
                @endforeach
            </div>
        </details>

        {{-- Actions --}}
        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan Kurikulum
            </button>
            <a href="{{ route('kurikulum.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>

    </div>
</form>

@endsection
