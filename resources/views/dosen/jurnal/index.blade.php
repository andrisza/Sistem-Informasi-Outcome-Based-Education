@extends('layouts.app')

@section('title', 'Jurnal Mengajar')
@section('header', 'Jurnal Mengajar')

@section('breadcrumb')
    <a href="{{ route('dosen.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Jurnal Mengajar</span>
@endsection

@section('header-actions')
    <a href="{{ route('dosen.jurnal.create') }}"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Jurnal
    </a>
@endsection

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
    <form method="GET" action="{{ route('dosen.jurnal.index') }}"
          class="flex flex-wrap items-center gap-3 p-4">
        <select name="bulan"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Bulan</option>
            @for ($b = 1; $b <= 12; $b++)
                <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                </option>
            @endfor
        </select>
        <select name="tahun"
                class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Tahun</option>
            @for ($y = now()->year; $y >= now()->year - 4; $y--)
                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Filter
        </button>
        @if (request()->anyFilled(['bulan','tahun']))
            <a href="{{ route('dosen.jurnal.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pertemuan ke</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Realisasi Materi</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jml Hadir</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($jurnalList as $jurnal)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5 text-gray-700 text-xs whitespace-nowrap">
                    {{ $jurnal->tanggal_pelaksanaan?->format('d M Y') ?? '—' }}
                </td>
                <td class="px-5 py-3.5">
                    <p class="font-medium text-gray-800 text-xs">{{ $jurnal->pertemuan?->rps?->mataKuliah?->nama_mk ?? '—' }}</p>
                    <p class="text-xs text-gray-400">{{ $jurnal->pertemuan?->rps?->semester?->nama ?? '' }}</p>
                </td>
                <td class="px-5 py-3.5 text-center font-semibold text-gray-700">
                    {{ $jurnal->pertemuan?->minggu_ke ?? '—' }}
                </td>
                <td class="px-5 py-3.5 text-gray-600 max-w-xs">
                    {{ Str::limit($jurnal->realisasi_materi, 80) }}
                </td>
                <td class="px-5 py-3.5 text-center text-gray-700 font-medium">
                    {{ $jurnal->jumlah_hadir }}
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('dosen.jurnal.show', $jurnal) }}"
                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9.75 0a9.75 9.75 0 0119.5 0 9.75 9.75 0 01-19.5 0z"/>
                            </svg>
                        </a>
                        <a href="{{ route('dosen.jurnal.edit', $jurnal) }}"
                           class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                    Belum ada jurnal mengajar.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if ($jurnalList->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $jurnalList->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
