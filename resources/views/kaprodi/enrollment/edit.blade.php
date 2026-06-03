@extends('layouts.app')
@section('title', 'Edit Enrollment')
@section('header', 'Edit Status Enrollment')

@section('breadcrumb')
    <a href="{{ route('kaprodi.enrollment.index') }}" class="hover:text-blue-600">Enrollment MK</a>
    <span class="mx-1">/</span>
    <span class="text-gray-700 font-medium">Edit</span>
@endsection

@section('content')
<div class="max-w-md">
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">

    {{-- Info --}}
    <div class="bg-gray-50 rounded-xl px-4 py-3 mb-5 space-y-1.5 text-sm">
        <div class="flex gap-2"><span class="text-gray-400 w-28">Mahasiswa</span><span class="font-medium">{{ $enrollment->mahasiswa?->name }}</span></div>
        <div class="flex gap-2"><span class="text-gray-400 w-28">NIM</span><span class="font-mono">{{ $enrollment->mahasiswa?->identifier ?? '—' }}</span></div>
        <div class="flex gap-2"><span class="text-gray-400 w-28">Mata Kuliah</span><span class="font-medium">{{ $enrollment->mataKuliah?->nama_mk }}</span></div>
        <div class="flex gap-2"><span class="text-gray-400 w-28">Semester</span><span>{{ $enrollment->semester?->nama }}</span></div>
    </div>

    <form method="POST" action="{{ route('kaprodi.enrollment.update', $enrollment) }}" class="space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
            <select name="status" required
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                @foreach (['aktif'=>'Aktif','mengulang'=>'Mengulang','lulus'=>'Lulus','tidak_lulus'=>'Tidak Lulus'] as $val => $lab)
                    <option value="{{ $val }}" {{ old('status', $enrollment->status) === $val ? 'selected' : '' }}>
                        {{ $lab }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Daftar</label>
            <input type="date" name="tanggal_daftar"
                   value="{{ old('tanggal_daftar', $enrollment->tanggal_daftar?->format('Y-m-d')) }}"
                   class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
        </div>

        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                Simpan Perubahan
            </button>
            <a href="{{ route('kaprodi.enrollment.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Batal</a>
        </div>
    </form>
</div>
</div>
@endsection
