@extends('layouts.app')

@section('title', 'Edit Anggota Tim')
@section('header', 'Edit Anggota Tim Kurikulum')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.tim.index', $kurikulum) }}" class="hover:text-blue-600">Tim</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Edit</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.tim.update', [$kurikulum, $tim]) }}" class="max-w-xl">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Periode <span class="text-red-500">*</span></label>
                <select name="id_periode"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_periode') border-red-400 @enderror">
                    <option value="">-- Pilih Periode --</option>
                    @foreach ($periodeList as $p)
                        <option value="{{ $p->id }}" {{ old('id_periode', $tim->id_periode) == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
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
                        <option value="{{ $user->id }}" {{ old('id_user', $tim->id_user) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('id_user') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Jabatan dalam Tim <span class="text-red-500">*</span></label>
                <input type="text" name="jabatan_tim" value="{{ old('jabatan_tim', $tim->jabatan_tim) }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jabatan_tim') border-red-400 @enderror">
                @error('jabatan_tim') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nomor SK</label>
                <input type="text" name="sk_nomor" value="{{ old('sk_nomor', $tim->sk_nomor) }}"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Perbarui
            </button>
            <a href="{{ route('kurikulum.tim.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
