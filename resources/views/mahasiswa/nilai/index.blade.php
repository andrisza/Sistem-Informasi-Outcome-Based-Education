@extends('layouts.app')

@section('title', 'Nilai Saya')
@section('header', 'Nilai Saya')

@section('breadcrumb')
    <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Nilai</span>
@endsection

@section('content')

@if ($grouped->isEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-6 py-14 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        <p class="text-gray-500 font-medium">Belum ada data nilai.</p>
        <p class="text-sm text-gray-400 mt-1">Data nilai akan muncul setelah Anda terdaftar di mata kuliah.</p>
    </div>
@else
    <div class="space-y-6">
        @foreach ($grouped as $semesterNama => $enrollments)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Header semester --}}
                <div class="flex items-center gap-3 px-5 py-4 bg-amber-50 border-b border-amber-100">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-amber-900 text-sm">{{ $semesterNama }}</h3>
                        <p class="text-xs text-amber-600">{{ $enrollments->count() }} mata kuliah</p>
                    </div>
                </div>

                {{-- Tabel MK --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama MK</th>
                                <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">SKS</th>
                                <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                                <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($enrollments as $enroll)
                                @php
                                    $statusColor = match($enroll->status) {
                                        'aktif'       => 'bg-blue-100 text-blue-700',
                                        'mengulang'   => 'bg-orange-100 text-orange-700',
                                        'lulus'       => 'bg-green-100 text-green-700',
                                        'tidak_lulus' => 'bg-red-100 text-red-700',
                                        default       => 'bg-gray-100 text-gray-600',
                                    };
                                    $nilaiColor = $enroll->nilai_akhir >= 56
                                        ? 'text-green-700 font-semibold'
                                        : ($enroll->nilai_akhir > 0 ? 'text-red-600 font-semibold' : 'text-gray-400');
                                @endphp
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-4 py-3 font-mono text-xs text-gray-500">
                                        {{ $enroll->mataKuliah?->kode_mk ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-800 font-medium">
                                        {{ $enroll->mataKuliah?->nama_mk ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-600">
                                        {{ $enroll->mataKuliah?->sks_total ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center {{ $nilaiColor }}">
                                        {{ $enroll->nilai_akhir > 0 ? number_format($enroll->nilai_akhir, 2) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $enroll->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('mahasiswa.nilai.show', [$enroll->id_mk, $enroll->id_semester]) }}"
                                           class="text-xs text-amber-600 hover:text-amber-700 font-medium hover:underline">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
