@extends('layouts.app')

@section('title', 'Tambah CPL Prodi')
@section('header', 'Tambah CPL Prodi')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.cpl-prodi.index', $kurikulum) }}" class="hover:text-blue-600">CPL Prodi</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Tambah</span>
@endsection

@section('content')

<form method="POST" action="{{ route('kurikulum.cpl-prodi.store', $kurikulum) }}" class="max-w-2xl">
    @csrf

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        <div class="px-6 py-5 space-y-4">

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="5"
                          class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 @error('deskripsi') border-red-400 @enderror"
                          placeholder="Deskripsi CPL Prodi...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Info referensi --}}
            <div class="rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 text-xs text-blue-700 flex items-start gap-2">
                <svg class="w-4 h-4 shrink-0 mt-0.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><strong>Referensi SN-Dikti</strong> akan terisi otomatis setelah CPL Prodi ini dipetakan ke CPL SN-Dikti melalui halaman <em>Pemetaan CPL-SN↔CPL-P</em>.</span>
            </div>

        </div>

        <div class="px-6 py-4 flex items-center gap-3">
            <button type="submit"
                    style="background:#7C3AED;color:#fff;font-size:14px;font-weight:600;padding:8px 20px;border-radius:8px;border:none;cursor:pointer">
                Simpan
            </button>
            <a href="{{ route('kurikulum.cpl-prodi.index', $kurikulum) }}"
               class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection
