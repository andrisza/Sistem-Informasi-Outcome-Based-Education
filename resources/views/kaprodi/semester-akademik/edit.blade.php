@extends('layouts.app')

@section('title', 'Edit Semester')
@section('header', 'Edit Semester Akademik')

@section('breadcrumb')
    <a href="{{ route('kaprodi.semester-akademik.index') }}" class="hover:text-blue-600">Semester Akademik</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Edit</span>
@endsection

@section('content')

<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('kaprodi.semester-akademik.update', $semesterAkademik) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-5">
                {{-- Tahun Akademik --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tahun Akademik <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tahun_akademik" value="{{ old('tahun_akademik', $semesterAkademik->tahun_akademik) }}" required
                           placeholder="2024/2025"
                           class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('tahun_akademik') ? 'border-red-400' : 'border-gray-300' }}">
                    @error('tahun_akademik')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Jenis --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jenis <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis" required
                            class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                   {{ $errors->has('jenis') ? 'border-red-400' : 'border-gray-300' }}">
                        <option value="Gasal"  {{ old('jenis', $semesterAkademik->jenis) === 'Gasal'  ? 'selected' : '' }}>Gasal</option>
                        <option value="Genap"  {{ old('jenis', $semesterAkademik->jenis) === 'Genap'  ? 'selected' : '' }}>Genap</option>
                    </select>
                    @error('jenis')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal Mulai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai"
                           value="{{ old('tanggal_mulai', $semesterAkademik->tanggal_mulai?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('tanggal_mulai')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai"
                           value="{{ old('tanggal_selesai', $semesterAkademik->tanggal_selesai?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('tanggal_selesai')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('kaprodi.semester-akademik.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
