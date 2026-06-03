@extends('layouts.app')
@section('title', 'Tambah Enrollment')
@section('header', 'Tambah Enrollment Mahasiswa')

@section('breadcrumb')
    <a href="{{ route('kaprodi.enrollment.index') }}" class="hover:text-blue-600">Enrollment MK</a>
    <span class="mx-1">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')
<div class="max-w-xl">
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('kaprodi.enrollment.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Mahasiswa <span class="text-red-500">*</span></label>
            <select name="id_mahasiswa" required
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $errors->has('id_mahasiswa') ? 'border-red-400' : '' }}">
                <option value="">— Pilih Mahasiswa —</option>
                @foreach ($mahasiswaList as $mhs)
                    <option value="{{ $mhs->id }}" {{ old('id_mahasiswa') == $mhs->id ? 'selected' : '' }}>
                        {{ $mhs->name }} ({{ $mhs->identifier ?? '—' }})
                    </option>
                @endforeach
            </select>
            @error('id_mahasiswa')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Mata Kuliah <span class="text-red-500">*</span></label>
            <select name="id_mk" required
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $errors->has('id_mk') ? 'border-red-400' : '' }}">
                <option value="">— Pilih Mata Kuliah —</option>
                @foreach ($mkList->groupBy('semester') as $smt => $mks)
                    <optgroup label="Semester {{ $smt }}">
                        @foreach ($mks as $mk)
                            <option value="{{ $mk->id }}" {{ old('id_mk') == $mk->id ? 'selected' : '' }}>
                                {{ $mk->kode_mk }} — {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('id_mk')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Semester <span class="text-red-500">*</span></label>
            <select name="id_semester" required
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 {{ $errors->has('id_semester') ? 'border-red-400' : '' }}">
                <option value="">— Pilih Semester —</option>
                @foreach ($semesters as $sem)
                    <option value="{{ $sem->id }}" {{ old('id_semester', $semesterAktif?->id) == $sem->id ? 'selected' : '' }}>
                        {{ $sem->nama }} — {{ $sem->tahun_akademik }}{{ $sem->is_aktif ? ' ★' : '' }}
                    </option>
                @endforeach
            </select>
            @error('id_semester')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="aktif" {{ old('status','aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="mengulang" {{ old('status') === 'mengulang' ? 'selected' : '' }}>Mengulang</option>
                    <option value="lulus" {{ old('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="tidak_lulus" {{ old('status') === 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Daftar</label>
                <input type="date" name="tanggal_daftar"
                       value="{{ old('tanggal_daftar', now()->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                Simpan
            </button>
            <a href="{{ route('kaprodi.enrollment.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Batal</a>
        </div>
    </form>
</div>
</div>
@endsection
