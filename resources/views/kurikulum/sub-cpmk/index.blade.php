@extends('layouts.app')

@section('title', 'Sub-CPMK – ' . $cpmk->kode_cpmk)
@section('header', 'Sub-CPMK – ' . $cpmk->kode_cpmk)

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.index', $kurikulum) }}" class="hover:text-blue-600">Mata Kuliah</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.cpmk.index', [$kurikulum, $mataKuliah]) }}" class="hover:text-blue-600">{{ $mataKuliah->kode_mk }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Sub-CPMK</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.create', [$kurikulum, $mataKuliah, $cpmk]) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Sub-CPMK
        </a>
    @endif
@endsection

@section('content')

@php $totalBobot = $subList->sum('bobot'); @endphp

{{-- CPMK info --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-4 flex items-start gap-4">
    <div class="flex-1 min-w-0">
        <p class="text-xs text-gray-500 mb-0.5">CPMK</p>
        <p class="text-sm font-semibold text-violet-700">{{ $cpmk->kode_cpmk }}</p>
        <p class="text-sm text-gray-600 mt-0.5">{{ $cpmk->deskripsi }}</p>
    </div>
    <div class="text-right shrink-0">
        <p class="text-xs text-gray-500 mb-0.5">Total Bobot</p>
        <p class="text-lg font-bold {{ $totalBobot > 100 ? 'text-red-600' : ($totalBobot == 100 ? 'text-emerald-600' : 'text-amber-600') }}">
            {{ number_format($totalBobot, 1) }}%
        </p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-8">No</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bobot (%)</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($subList as $sub)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $sub->urutan }}</td>
                    <td class="px-5 py-3.5 font-mono text-xs font-semibold text-emerald-700">{{ $sub->kode_sub_cpmk }}</td>
                    <td class="px-5 py-3.5 text-gray-700 text-sm max-w-sm">{{ Str::limit($sub->deskripsi, 100) }}</td>
                    <td class="px-5 py-3.5 text-center font-semibold text-gray-800 text-sm">{{ number_format($sub->bobot, 1) }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1.5">
                            @if (!$kurikulum->isArsip())
                                <a href="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.edit', [$kurikulum, $mataKuliah, $cpmk, $sub]) }}"
                                   class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.destroy', [$kurikulum, $mataKuliah, $cpmk, $sub]) }}"
                                      onsubmit="return confirm('Hapus Sub-CPMK {{ $sub->kode_sub_cpmk }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-gray-400 text-sm">
                        Belum ada Sub-CPMK.
                        @if (!$kurikulum->isArsip())
                            <a href="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.create', [$kurikulum, $mataKuliah, $cpmk]) }}" class="text-blue-600 hover:underline ml-1">Tambah sekarang</a>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
