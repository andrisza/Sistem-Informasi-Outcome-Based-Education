@extends('layouts.app')

@section('title', 'Profil Lulusan – ' . $kurikulum->kode)
@section('header', 'Profil Lulusan (PL)')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Profil Lulusan</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.pl.create', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah PL
        </a>
    @endif
@endsection

@section('content')

{{-- Info banner --}}
<div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-4 text-xs text-amber-800">
    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Profil Lulusan menggambarkan peran, fungsi, dan kompetensi yang dicapai lulusan program studi. Setiap PL akan dipetakan ke CPL Prodi.</span>
    <span class="ml-auto shrink-0 font-semibold">{{ $plList->count() }} PL terdaftar</span>
</div>

<div class="rounded-xl border border-amber-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse obe-list-table">
            <thead>
                <tr style="background:#F59E0B">
                    <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-300 w-10">No</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-300 w-24">Kode PL</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-300">Profil Lulusan (PL)</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-300 w-36">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-300 w-40">Referensi</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-300 w-24">Status</th>
                    @if (!$kurikulum->isArsip())
                        <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-300 w-28">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($plList as $pl)
                    <tr class="border-b border-amber-100 hover:bg-amber-50 transition-colors" style="{{ $loop->even ? 'background:#fffbeb' : 'background:#fff' }}">
                        <td class="px-3 py-3 text-center text-xs text-gray-500 border border-amber-100 font-medium">{{ $pl->urutan }}</td>
                        <td class="px-4 py-3 text-center border border-amber-100">
                            <span class="font-mono font-bold text-amber-800 text-xs bg-amber-100 px-2 py-0.5 rounded cursor-help"
                                  data-tooltip="{{ $pl->kode_pl }}: {{ $pl->deskripsi }}"
                                  data-tip-label="Profil Lulusan">{{ $pl->kode_pl }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-700 text-sm border border-amber-100 leading-relaxed">{{ $pl->deskripsi }}</td>
                        <td class="px-4 py-3 border border-amber-100">
                            @if ($pl->kategori)
                                @php
                                    $kCls = match(true) {
                                        str_contains($pl->kategori, 'Utama')        => 'bg-blue-100 text-blue-800',
                                        str_contains($pl->kategori, 'Sikap')        => 'bg-green-100 text-green-800',
                                        str_contains($pl->kategori, 'Keterampilan') => 'bg-violet-100 text-violet-800',
                                        default                                      => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium {{ $kCls }}">{{ $pl->kategori }}</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border border-amber-100 text-xs text-gray-500 leading-snug">
                            {{ $pl->referensi ? Str::limit($pl->referensi, 60) : '—' }}
                        </td>
                        <td class="px-4 py-3 text-center border border-amber-100">
                            @if (($pl->status ?? 'draft') === 'approved')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Approved</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Draft</span>
                            @endif
                        </td>
                        @if (!$kurikulum->isArsip())
                            <td class="px-3 py-3 border border-amber-100">
                                <div class="flex items-center justify-center gap-1 flex-wrap">
                                    @if (auth()->user()->role->value === 'kaprodi' && ($pl->status ?? 'draft') === 'draft')
                                        <form method="POST" action="{{ route('kurikulum.pl.approve', [$kurikulum, $pl]) }}"
                                              onsubmit="return confirm('Setujui PL {{ $pl->kode_pl }}?')">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded transition-colors" title="Approve">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('kurikulum.pl.edit', [$kurikulum, $pl]) }}"
                                       class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('kurikulum.pl.destroy', [$kurikulum, $pl]) }}"
                                          onsubmit="return confirm('Hapus PL {{ $pl->kode_pl }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $kurikulum->isArsip() ? 6 : 7 }}" class="px-5 py-14 text-center text-sm text-gray-400 bg-amber-50/30">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Belum ada Profil Lulusan.</span>
                                @if (!$kurikulum->isArsip())
                                    <a href="{{ route('kurikulum.pl.create', $kurikulum) }}" class="text-amber-600 hover:underline font-medium">Tambah PL pertama →</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
