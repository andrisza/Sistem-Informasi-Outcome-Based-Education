@extends('layouts.app')

@section('title', 'Edit RPS')
@section('header', 'Edit RPS')

@section('breadcrumb')
    <a href="{{ route('dosen.rps.index') }}" class="hover:text-blue-600">RPS Saya</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('dosen.rps.show', $rps) }}" class="hover:text-blue-600">Detail</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Edit</span>
@endsection

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('dosen.rps.update', $rps) }}" class="space-y-5">
            @csrf @method('PUT')

            {{-- Pilih MK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <select name="id_mk" required
                        class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                               {{ $errors->has('id_mk') ? 'border-red-400' : 'border-gray-300' }}">
                    <option value="">— Pilih Mata Kuliah —</option>
                    @foreach ($pengampuan as $p)
                        <option value="{{ $p->id_mk }}"
                                {{ old('id_mk', $rps->id_mk) == $p->id_mk ? 'selected' : '' }}>
                            {{ $p->mataKuliah->kode_mk ?? '' }} — {{ $p->mataKuliah->nama_mk ?? '' }}
                            ({{ $p->semester->nama ?? '' }})
                        </option>
                    @endforeach
                </select>
                @error('id_mk')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Pilih Semester --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Semester <span class="text-red-500">*</span>
                </label>
                <select name="id_semester" required
                        class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                               {{ $errors->has('id_semester') ? 'border-red-400' : 'border-gray-300' }}">
                    <option value="">— Pilih Semester —</option>
                    @foreach ($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ old('id_semester', $rps->id_semester) == $sem->id ? 'selected' : '' }}>
                            {{ $sem->nama }} — {{ $sem->tahun_akademik }}
                            @if($sem->is_aktif) (Aktif) @endif
                        </option>
                    @endforeach
                </select>
                @error('id_semester')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Tanggal Penyusunan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tanggal Penyusunan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_penyusunan"
                           value="{{ old('tanggal_penyusunan', $rps->tanggal_penyusunan?->format('Y-m-d')) }}" required
                           class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                                  {{ $errors->has('tanggal_penyusunan') ? 'border-red-400' : 'border-gray-300' }}">
                    @error('tanggal_penyusunan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Kode Dokumen --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kode Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_dokumen"
                           value="{{ old('kode_dokumen', $rps->kode_dokumen) }}" required
                           class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                                  {{ $errors->has('kode_dokumen') ? 'border-red-400' : 'border-gray-300' }}">
                    @error('kode_dokumen')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('dosen.rps.show', $rps) }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
