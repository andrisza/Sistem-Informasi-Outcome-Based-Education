@extends('layouts.app')

@section('title', 'Repositori Materi')
@section('header', 'Repositori Materi Pembelajaran')

@section('header-actions')
    <a href="{{ route('dosen.materi.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Upload Materi
    </a>
@endsection

@section('content')

{{-- Filter --}}
<form method="GET" class="flex flex-wrap gap-3 mb-5">
    <select name="mk" class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua MK</option>
        @foreach ($mkDiampu as $mk)
            <option value="{{ $mk->id }}" {{ request('mk') == $mk->id ? 'selected' : '' }}>{{ $mk->kode_mk }} – {{ $mk->nama_mk }}</option>
        @endforeach
    </select>
    <select name="jenis" class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Jenis</option>
        @foreach (['modul', 'presentasi', 'referensi', 'tugas', 'video', 'lainnya'] as $j)
            <option value="{{ $j }}" {{ request('jenis') == $j ? 'selected' : '' }}>{{ ucfirst($j) }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white text-sm px-4 py-2 rounded-lg transition-colors">Filter</button>
    @if (request('mk') || request('jenis'))
        <a href="{{ route('dosen.materi.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">Reset</a>
    @endif
</form>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama File</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">MK</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Upload</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($materiList as $materi)
                @php
                    $jenisColor = match($materi->jenis_file) {
                        'modul'       => 'bg-blue-100 text-blue-700',
                        'presentasi'  => 'bg-violet-100 text-violet-700',
                        'referensi'   => 'bg-amber-100 text-amber-700',
                        'tugas'       => 'bg-emerald-100 text-emerald-700',
                        'video'       => 'bg-red-100 text-red-700',
                        default       => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-gray-800">{{ $materi->nama_file }}</p>
                        @if ($materi->deskripsi)
                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($materi->deskripsi, 60) }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-xs font-mono text-blue-700">{{ $materi->mataKuliah?->kode_mk ?? '–' }}</td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jenisColor }}">
                            {{ ucfirst($materi->jenis_file) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">{{ $materi->semester?->nama_semester ?? '–' }}</td>
                    <td class="px-5 py-3.5 text-xs text-gray-400">{{ $materi->created_at?->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <form method="POST" action="{{ route('dosen.materi.destroy', $materi) }}"
                              onsubmit="return confirm('Hapus materi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                        Belum ada materi yang diunggah.
                        <a href="{{ route('dosen.materi.create') }}" class="text-blue-600 hover:underline ml-1">Upload sekarang</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($materiList->hasPages())
    <div class="mt-4">{{ $materiList->links() }}</div>
@endif

@endsection
