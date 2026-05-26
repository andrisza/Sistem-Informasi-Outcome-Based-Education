@extends('layouts.app')

@section('title', 'Tambah Anggota Tim')
@section('header', 'Tambah Anggota Tim Kurikulum')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.tim.index', $kurikulum) }}" class="hover:text-blue-600">Tim</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.tim.store', $kurikulum) }}" class="max-w-xl">
    @csrf

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3 mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Periode <span class="text-red-500">*</span></label>
                <select name="id_periode"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_periode') border-red-400 @enderror">
                    <option value="">-- Pilih Periode --</option>
                    @foreach ($periodeList as $p)
                        <option value="{{ $p->id }}" {{ old('id_periode') == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
                    @endforeach
                </select>
                @error('id_periode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Anggota <span class="text-red-500">*</span></label>
                <select name="id_user"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_user') border-red-400 @enderror">
                    <option value="">-- Pilih Pengguna --</option>
                    @foreach ($userList as $user)
                        <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->role->value ?? $user->role }})
                        </option>
                    @endforeach
                </select>
                @error('id_user') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Jabatan dalam Tim <span class="text-red-500">*</span></label>
                <input type="text" name="jabatan_tim" value="{{ old('jabatan_tim') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jabatan_tim') border-red-400 @enderror"
                       placeholder="cth: Anggota, Sekretaris, Ketua">
                @error('jabatan_tim') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nomor SK</label>
                <input type="text" name="sk_nomor" value="{{ old('sk_nomor') }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Nomor SK (opsional)">
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan
            </button>
            <a href="{{ route('kurikulum.tim.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
