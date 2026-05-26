@extends('layouts.app')

@section('title', 'Periode Kurikulum')
@section('header', 'Periode Penyusunan Kurikulum')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Periode</span>
@endsection

@section('header-actions')
    @if (!$kurikulum->isArsip())
        <a href="{{ route('kurikulum.periode.create', $kurikulum) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Periode
        </a>
    @endif
@endsection

@section('content')

<div class="space-y-4">
    @forelse ($periodeList as $periode)
        @php
            $sColor = match($periode->status) {
                'berjalan'    => 'bg-emerald-100 text-emerald-700',
                'selesai'     => 'bg-gray-100 text-gray-600',
                'perencanaan' => 'bg-blue-100 text-blue-700',
                default       => 'bg-gray-100 text-gray-600',
            };
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="text-sm font-semibold text-gray-800">{{ $periode->nama_periode }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $sColor }}">
                            {{ ucfirst($periode->status) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span>{{ $periode->tanggal_mulai?->format('d M Y') ?? '–' }} – {{ $periode->tanggal_selesai?->format('d M Y') ?? 'sekarang' }}</span>
                        @if ($periode->ketuaTim)
                            <span>Ketua: {{ $periode->ketuaTim->name }}</span>
                        @endif
                    </div>
                    @if ($periode->deskripsi)
                        <p class="text-xs text-gray-500 mt-2">{{ Str::limit($periode->deskripsi, 120) }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    @if (!$kurikulum->isArsip())
                        <a href="{{ route('kurikulum.periode.edit', [$kurikulum, $periode]) }}"
                           class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('kurikulum.periode.destroy', [$kurikulum, $periode]) }}"
                              onsubmit="return confirm('Hapus periode ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-12 text-center text-gray-400 text-sm">
            Belum ada periode penyusunan kurikulum.
            @if (!$kurikulum->isArsip())
                <a href="{{ route('kurikulum.periode.create', $kurikulum) }}" class="text-blue-600 hover:underline ml-1">Tambah sekarang</a>
            @endif
        </div>
    @endforelse
</div>

@endsection
