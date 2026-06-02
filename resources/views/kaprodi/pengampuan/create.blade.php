@extends('layouts.app')

@section('title', 'Tambah Pengampuan MK')
@section('header', 'Tambah Pengampuan Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('kaprodi.pengampuan.index') }}" class="hover:text-blue-600">Pengampuan MK</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')

<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('kaprodi.pengampuan.store') }}" class="space-y-5">
            @csrf

            {{-- Semester --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Semester <span class="text-red-500">*</span>
                </label>
                <select name="id_semester" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                               @error('id_semester') border-red-400 @enderror">
                    <option value="">— Pilih Semester —</option>
                    @foreach ($semesters as $sem)
                        <option value="{{ $sem->id }}"
                                {{ old('id_semester') == $sem->id ? 'selected' : '' }}
                                {{ $sem->is_aktif ? 'selected' : '' }}>
                            {{ $sem->nama }}{{ $sem->is_aktif ? ' ★ (Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('id_semester')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Mata Kuliah --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <select name="id_mk" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                               @error('id_mk') border-red-400 @enderror">
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
                @error('id_mk')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Dosen --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Dosen Pengampu <span class="text-red-500">*</span>
                </label>
                <select name="id_dosen" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                               @error('id_dosen') border-red-400 @enderror">
                    <option value="">— Pilih Dosen —</option>
                    @foreach ($dosenList as $d)
                        <option value="{{ $d->id }}" {{ old('id_dosen') == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
                @error('id_dosen')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Koordinator --}}
            <div class="flex items-start gap-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <input type="checkbox" name="is_koordinator" value="1" id="is_koordinator"
                       {{ old('is_koordinator') ? 'checked' : '' }}
                       class="mt-0.5 rounded border-amber-300 text-amber-600">
                <label for="is_koordinator" class="text-sm cursor-pointer">
                    <span class="font-medium text-amber-800">Jadikan sebagai Koordinator MK</span>
                    <span class="text-xs text-amber-600 block mt-0.5">
                        Koordinator dapat mengedit dan mengajukan RPS, meskipun bukan yang membuat RPS tersebut.
                        Hanya 1 koordinator per MK per semester.
                    </span>
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Tambah Pengampuan
                </button>
                <a href="{{ route('kaprodi.pengampuan.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
