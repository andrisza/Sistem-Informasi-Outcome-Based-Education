@extends('layouts.app')

@section('title', 'RPS — ' . ($mataKuliah->nama_mk ?? '-'))
@section('header', 'Rencana Pembelajaran Semester')

@section('breadcrumb')
    <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">RPS {{ $mataKuliah->kode_mk ?? '' }}</span>
@endsection

@section('content')

<div class="max-w-4xl space-y-5">

    {{-- Info RPS Header --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between mb-5">
            <div>
                <p class="text-xs font-mono text-gray-400 mb-1">{{ $mataKuliah->kode_mk }}</p>
                <h3 class="text-lg font-bold text-gray-900">{{ $mataKuliah->nama_mk }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">{{ $semesterAkademik->nama }} · {{ $mataKuliah->sks_total }} SKS</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Disahkan
            </span>
        </div>

        <dl class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Dosen Pengembang</dt>
                <dd class="text-gray-800">{{ $rps->dosenPengembang?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tanggal Penyusunan</dt>
                <dd class="text-gray-800">{{ $rps->tanggal_penyusunan?->format('d M Y') ?? '—' }}</dd>
            </div>
            @if ($rps->kode_dokumen)
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Kode Dokumen</dt>
                <dd class="text-gray-800 font-mono">{{ $rps->kode_dokumen }}</dd>
            </div>
            @endif
            @if ($rps->disahkan_pada)
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Disahkan Pada</dt>
                <dd class="text-gray-800">{{ $rps->disahkan_pada->format('d M Y, H:i') }}</dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- Tabel Pertemuan --}}
    @if ($rps->pertemuan && $rps->pertemuan->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h4 class="font-semibold text-gray-800 text-sm">
                Rencana Pertemuan
                <span class="ml-1 text-xs text-gray-400 font-normal">({{ $rps->pertemuan->count() }} pertemuan)</span>
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">Prt.</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kemampuan Akhir</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Metode</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bahan Kajian</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Durasi</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">Bobot</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($rps->pertemuan->sortBy('pertemuan_ke') as $prt)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                    {{ $prt->pertemuan_ke }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 leading-relaxed text-xs">
                                {{ $prt->kemampuan_akhir ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $prt->metode_pembelajaran ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $prt->bahan_kajian ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-500 text-xs">
                                {{ $prt->durasi_menit ? $prt->durasi_menit . ' mnt' : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-500 text-xs">
                                {{ $prt->bobot_penilaian ? $prt->bobot_penilaian . '%' : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-8 text-center">
        <p class="text-sm text-gray-400">Belum ada rencana pertemuan yang ditambahkan ke RPS ini.</p>
    </div>
    @endif

    {{-- Navigasi kembali --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 font-medium">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
        <span class="text-gray-200">|</span>
        <a href="{{ route('mahasiswa.nilai.show', [$mataKuliah->id, $semesterAkademik->id]) }}"
           class="text-sm text-amber-600 hover:text-amber-700 font-medium hover:underline">
            Lihat Nilai →
        </a>
    </div>

</div>

@endsection
