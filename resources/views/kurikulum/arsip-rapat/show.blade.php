@extends('layouts.app')

@section('title', $arsipRapat->judul_kegiatan)
@section('header', 'Detail Arsip Kegiatan')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.arsip-rapat.index', $kurikulum) }}" class="hover:text-blue-600">Arsip Rapat</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ Str::limit($arsipRapat->judul_kegiatan, 40) }}</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.arsip-rapat.edit', [$kurikulum, $arsipRapat]) }}"
           class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
    @endif
    <a href="{{ route('kurikulum.arsip-rapat.index', $kurikulum) }}"
       class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        ← Kembali
    </a>
@endsection

@section('content')

@php $files = $arsipRapat->file_lampiran ?? []; @endphp

<div class="max-w-3xl space-y-4">

    {{-- Header card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-bold text-gray-900 text-base leading-snug">{{ $arsipRapat->judul_kegiatan }}</h2>
            </div>
            @if (!$kurikulum->isArsip())
                <a href="{{ route('kurikulum.arsip-rapat.edit', [$kurikulum, $arsipRapat]) }}"
                   class="shrink-0 text-xs text-blue-600 hover:underline font-medium">Edit →</a>
            @endif
        </div>

        {{-- Meta grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 divide-x divide-gray-100 border-t border-gray-100">
            <div class="px-5 py-3">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Tanggal</p>
                <p class="text-sm font-semibold text-gray-800">
                    {{ $arsipRapat->tanggal?->translatedFormat('d F Y') ?? '–' }}
                </p>
            </div>
            <div class="px-5 py-3">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Tempat</p>
                <p class="text-sm font-semibold text-gray-800">{{ $arsipRapat->tempat ?: '–' }}</p>
            </div>
            <div class="px-5 py-3">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Dibuat oleh</p>
                <p class="text-sm font-semibold text-gray-800">{{ $arsipRapat->pembuat?->name ?? '–' }}</p>
                <p class="text-[10px] text-gray-400">{{ $arsipRapat->created_at?->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Temuan --}}
    @if ($arsipRapat->temuan)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Temuan</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $arsipRapat->temuan }}</p>
        </div>
    </div>
    @endif

    {{-- Tindak Lanjut --}}
    @if ($arsipRapat->tindak_lanjut)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Tindak Lanjut</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $arsipRapat->tindak_lanjut }}</p>
        </div>
    </div>
    @endif

    {{-- Bukti Rapat --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Bukti Rapat</h3>
                @if ($files)
                    <span class="text-xs text-gray-400">({{ count($files) }} file)</span>
                @endif
            </div>
            @if (!$kurikulum->isArsip())
                <a href="{{ route('kurikulum.arsip-rapat.edit', [$kurikulum, $arsipRapat]) }}"
                   class="text-xs text-blue-600 hover:underline">+ Tambah file</a>
            @endif
        </div>
        <div class="px-5 py-4">
            @if ($files)
                <div class="space-y-2">
                    @foreach ($files as $file)
                        @php
                            $ext   = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                            $isImg = in_array($ext, ['png','jpg','jpeg','heic','heif']);
                            $isPdf = $ext === 'pdf';
                            $iconColor = $isImg ? 'text-emerald-500' : ($isPdf ? 'text-red-500' : 'text-blue-500');
                        @endphp
                        <a href="{{ Storage::url($file['path']) }}" target="_blank"
                           class="flex items-center gap-3 px-3.5 py-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                            @if ($isImg)
                                <svg class="w-5 h-5 {{ $iconColor }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                </svg>
                            @elseif ($isPdf)
                                <svg class="w-5 h-5 {{ $iconColor }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 {{ $iconColor }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-800 truncate group-hover:text-blue-700">{{ $file['name'] }}</p>
                                <p class="text-[10px] text-gray-400 uppercase">{{ $ext }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 italic">Belum ada file bukti rapat.
                    @if (!$kurikulum->isArsip())
                        <a href="{{ route('kurikulum.arsip-rapat.edit', [$kurikulum, $arsipRapat]) }}"
                           class="text-blue-600 hover:underline not-italic font-medium ml-1">Upload sekarang →</a>
                    @endif
                </p>
            @endif
        </div>
    </div>

</div>

@endsection
