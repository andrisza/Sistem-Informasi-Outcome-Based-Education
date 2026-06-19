@extends('layouts.app')

@section('title', 'Arsip Rapat')
@section('header', 'Dokumentasi Rapat Kurikulum')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Arsip Rapat</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip() && $hasAny)
        <a href="{{ route('kurikulum.arsip-rapat.create', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Arsip
        </a>
    @endif
@endsection

@section('content')

@if ($arsipList->isEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-20 flex flex-col items-center justify-center text-center">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
        </div>
        <p class="text-gray-800 font-semibold text-base mb-1">Belum ada arsip rapat</p>
        <p class="text-sm text-gray-400 mb-6">Dokumentasikan rapat kurikulum pertama Anda di sini.</p>
        @if (!$kurikulum->isArsip())
            <a href="{{ route('kurikulum.arsip-rapat.create', $kurikulum) }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Arsip Rapat
            </a>
        @endif
    </div>
@else
    <div class="space-y-3">
        @foreach ($arsipList as $arsip)
            @php
                $hasCatatan = !empty($arsip->temuan) || !empty($arsip->tindak_lanjut);
                $fileCnt    = count($arsip->file_lampiran ?? []);
            @endphp

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:border-blue-200 hover:shadow-md transition-all">
                <div class="flex items-start gap-4 px-5 py-4">

                    {{-- Date badge --}}
                    <div class="shrink-0 text-center bg-slate-50 border border-slate-100 rounded-lg px-3 py-2 min-w-[56px]">
                        <p class="text-xs font-bold text-slate-600">{{ $arsip->tanggal?->format('d') ?? '—' }}</p>
                        <p class="text-[10px] text-slate-400 uppercase">{{ $arsip->tanggal?->format('M Y') ?? '' }}</p>
                    </div>

                    {{-- Main content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                            <div class="min-w-0">
                                <a href="{{ route('kurikulum.arsip-rapat.show', [$kurikulum, $arsip]) }}"
                                   class="font-semibold text-gray-800 text-sm hover:text-blue-600 transition-colors block truncate">
                                    {{ $arsip->judul_kegiatan }}
                                </a>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    @if ($arsip->tempat)
                                        <span class="text-xs text-gray-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $arsip->tempat }}
                                        </span>
                                    @endif
                                    @if ($arsip->pembuat)
                                        <span class="text-xs text-gray-400">oleh {{ $arsip->pembuat->name }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Indikator catatan & file --}}
                            <div class="shrink-0 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 text-[10px] font-medium {{ $hasCatatan ? 'text-emerald-600' : 'text-gray-300' }}">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $hasCatatan ? 2.5 : 1.5 }}">
                                        @if ($hasCatatan)
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        @else
                                            <circle cx="12" cy="12" r="9"/>
                                        @endif
                                    </svg>
                                    Catatan
                                </span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-medium {{ $fileCnt > 0 ? 'text-blue-600' : 'text-gray-300' }}">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $fileCnt > 0 ? 2 : 1.5 }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    {{ $fileCnt > 0 ? $fileCnt . ' file' : 'Bukti' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('kurikulum.arsip-rapat.show', [$kurikulum, $arsip]) }}"
                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        @if (!$kurikulum->isArsip())
                            <a href="{{ route('kurikulum.arsip-rapat.edit', [$kurikulum, $arsip]) }}"
                               class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('kurikulum.arsip-rapat.destroy', [$kurikulum, $arsip]) }}"
                                  onsubmit="return confirm('Hapus arsip rapat ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($arsipList->hasPages())
        <div class="mt-5">{{ $arsipList->links() }}</div>
    @endif
@endif

@endsection
