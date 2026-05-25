@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('header', 'Tambah Pengguna')

@section('breadcrumb')
    <a href="{{ route('kaprodi.users.index') }}" class="hover:text-blue-600">Pengguna</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('kaprodi.users.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Nama --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }}">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }}">
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300' }}">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Peran --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Peran <span class="text-red-500">*</span></label>
                    <select name="role" required
                            class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                   {{ $errors->has('role') ? 'border-red-400' : 'border-gray-300' }}">
                        <option value="">— Pilih Peran —</option>
                        <option value="kaprodi"       {{ old('role') === 'kaprodi'       ? 'selected' : '' }}>Kaprodi</option>
                        <option value="tim_kurikulum" {{ old('role') === 'tim_kurikulum' ? 'selected' : '' }}>Tim Kurikulum</option>
                        <option value="dosen"         {{ old('role') === 'dosen'         ? 'selected' : '' }}>Dosen</option>
                        <option value="mahasiswa"     {{ old('role') === 'mahasiswa'     ? 'selected' : '' }}>Mahasiswa</option>
                    </select>
                    @error('role')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Identifier (NIDN/NIM) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Identifier
                        <span class="text-gray-400 font-normal">(NIDN/NIP/NIM)</span>
                    </label>
                    <input type="text" name="identifier" value="{{ old('identifier') }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('identifier')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Program Studi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Program Studi</label>
                    <input type="text" name="program_studi" value="{{ old('program_studi') }}"
                           placeholder="Sistem Informasi"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Tahun Masuk (mahasiswa) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tahun Masuk
                        <span class="text-gray-400 font-normal">(khusus mahasiswa)</span>
                    </label>
                    <input type="number" name="tahun_masuk" value="{{ old('tahun_masuk') }}"
                           min="2000" max="{{ date('Y') }}" placeholder="{{ date('Y') }}"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Aktif</label>
                    <select name="status_aktif"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="aktif"    {{ old('status_aktif', 'aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status_aktif') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                        <option value="cuti"     {{ old('status_aktif') === 'cuti'     ? 'selected' : '' }}>Cuti</option>
                    </select>
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Simpan Pengguna
                </button>
                <a href="{{ route('kaprodi.users.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
