@extends('layouts.app')

@section('title', 'CPMK – ' . $mataKuliah->kode_mk)
@section('header', 'CPMK – ' . $mataKuliah->nama_mk)

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.index', $kurikulum) }}" class="hover:text-blue-600">Mata Kuliah</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.mata-kuliah.show', [$kurikulum, $mataKuliah]) }}" class="hover:text-blue-600">{{ $mataKuliah->kode_mk }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">CPMK</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.mata-kuliah.cpmk.create', [$kurikulum, $mataKuliah]) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah CPMK
        </a>
    @endif
@endsection

@section('content')

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-8">No</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">CPL Terkait</th>
                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sub-CPMK</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($cpmkList as $cpmk)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $cpmk->urutan }}</td>
                    <td class="px-5 py-3.5 font-mono text-xs font-semibold text-violet-700">{{ $cpmk->kode_cpmk }}</td>
                    <td class="px-5 py-3.5 text-gray-700 text-sm max-w-sm">{{ Str::limit($cpmk->deskripsi, 100) }}</td>
                    <td class="px-5 py-3.5 text-xs text-gray-500 font-mono">{{ $cpmk->cplProdi?->kode_cpl ?? '–' }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <a href="{{ route('kurikulum.mata-kuliah.cpmk.sub-cpmk.index', [$kurikulum, $mataKuliah, $cpmk]) }}"
                           class="text-xs font-medium text-blue-600 hover:underline">{{ $cpmk->sub_cpmk_count }} Sub</a>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1.5">
                            @if (!$kurikulum->isArsip())
                                <a href="{{ route('kurikulum.mata-kuliah.cpmk.edit', [$kurikulum, $mataKuliah, $cpmk]) }}"
                                   class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('kurikulum.mata-kuliah.cpmk.destroy', [$kurikulum, $mataKuliah, $cpmk]) }}"
                                      onsubmit="return confirm('Hapus CPMK {{ $cpmk->kode_cpmk }}?')">
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
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                        Belum ada CPMK untuk mata kuliah ini.
                        @if (!$kurikulum->isArsip())
                            <a href="{{ route('kurikulum.mata-kuliah.cpmk.create', [$kurikulum, $mataKuliah]) }}" class="text-blue-600 hover:underline ml-1">Tambah sekarang</a>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
