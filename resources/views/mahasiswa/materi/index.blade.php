@extends('layouts.app')

@section('title', 'Materi Pembelajaran')
@section('header', 'Materi Pembelajaran')

@section('breadcrumb')
    <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Materi</span>
@endsection

@section('content')

{{-- Filter bar --}}
<form method="GET" action="{{ route('mahasiswa.materi.index') }}"
      class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 mb-5 flex flex-wrap items-end gap-4">
    <div class="flex-1 min-w-48">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Filter Mata Kuliah</label>
        <select name="mk"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="">Semua Mata Kuliah</option>
            @foreach ($mkList as $mk)
                <option value="{{ $mk->id }}" @selected($filterMk == $mk->id)>
                    {{ $mk->kode_mk }} — {{ $mk->nama_mk }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex-1 min-w-36">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Jenis File</label>
        <select name="jenis"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="">Semua Jenis</option>
            @foreach ($jenisList as $jenis)
                <option value="{{ $jenis }}" @selected($filterJenis === $jenis)>
                    {{ strtoupper($jenis) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit"
                class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            Terapkan
        </button>
        @if ($filterMk || $filterJenis)
            <a href="{{ route('mahasiswa.materi.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Reset
            </a>
        @endif
    </div>
</form>

{{-- Tabel Materi --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 text-sm">
            Daftar Materi
            <span class="ml-1 text-xs text-gray-400 font-normal">({{ $materiList->count() }} file)</span>
        </h3>
    </div>

    @if ($materiList->isEmpty())
        <div class="px-5 py-14 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <p class="text-gray-500 font-medium">Belum ada materi tersedia.</p>
            @if ($filterMk || $filterJenis)
                <p class="text-sm text-gray-400 mt-1">Coba ubah filter pencarian.</p>
            @else
                <p class="text-sm text-gray-400 mt-1">Materi akan muncul setelah dosen mengunggah file.</p>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama File</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Jenis</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Ukuran</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Tanggal</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($materiList as $materi)
                        @php
                            $jenisColor = match(strtolower($materi->jenis_file ?? '')) {
                                'pdf'           => 'bg-red-100 text-red-700',
                                'pptx', 'ppt'   => 'bg-orange-100 text-orange-700',
                                'docx', 'doc'   => 'bg-blue-100 text-blue-700',
                                'xlsx', 'xls'   => 'bg-green-100 text-green-700',
                                'mp4', 'avi', 'mov' => 'bg-purple-100 text-purple-700',
                                'jpg', 'jpeg', 'png', 'gif' => 'bg-pink-100 text-pink-700',
                                'zip', 'rar'    => 'bg-yellow-100 text-yellow-700',
                                default         => 'bg-gray-100 text-gray-600',
                            };
                            $ukuran = $materi->ukuran_kb >= 1024
                                ? round($materi->ukuran_kb / 1024, 1) . ' MB'
                                : ($materi->ukuran_kb . ' KB');
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-gray-800 font-medium truncate max-w-xs">{{ $materi->nama_file }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                <p class="font-medium">{{ $materi->mataKuliah?->nama_mk ?? '-' }}</p>
                                <p class="text-gray-400">{{ $materi->semester?->nama ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block text-xs font-semibold uppercase px-2 py-0.5 rounded {{ $jenisColor }}">
                                    {{ $materi->jenis_file ?? '?' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs max-w-xs truncate">
                                {{ $materi->deskripsi ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-500 text-xs">
                                {{ $ukuran }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-400 text-xs">
                                {{ $materi->created_at?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('mahasiswa.materi.show', $materi) }}"
                                   class="text-xs text-amber-600 hover:text-amber-700 font-medium hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
