@extends('layouts.app')

@section('title', 'Laporan CPL – ' . $kurikulum->nama_kurikulum)
@section('header', 'Ketercapaian CPL')

@section('breadcrumb')
    <a href="{{ route('kaprodi.reports.index') }}" class="hover:text-blue-600">Laporan</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">CPL · {{ $kurikulum->kode }}</span>
@endsection

@section('header-actions')
    <button onclick="window.print()"
            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Cetak
    </button>
@endsection

@section('content')

{{-- Ringkasan Kurikulum --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Kurikulum</p>
            <p class="font-medium text-gray-800 mt-0.5">{{ $kurikulum->nama_kurikulum }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Tahun Mulai</p>
            <p class="font-medium text-gray-800 mt-0.5">{{ $kurikulum->tahun_mulai }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Status</p>
            <p class="font-medium text-gray-800 mt-0.5">{{ ucfirst($kurikulum->status) }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Entri Data</p>
            <p class="font-medium text-gray-800 mt-0.5">{{ $hasilCpl->count() }} baris</p>
        </div>
    </div>
</div>

{{-- Tabel Hasil CPL --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h4 class="font-semibold text-gray-800 text-sm">Ketercapaian CPL per Semester</h4>
        <p class="text-xs text-gray-400 mt-0.5">Rata-rata nilai CPL seluruh mahasiswa, dikelompokkan per CPL dan semester.</p>
    </div>
    @if ($hasilCpl->count())
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">CPL</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rata-rata</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mhs</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tercapai</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Batas</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($hasilCpl as $hasil)
                    @php
                        $batas = $hasil->batas_nilai ?? 65;
                        $tercapai = $hasil->nilai_rata_rata >= $batas;
                        $pct = $hasil->jumlah_mahasiswa > 0
                            ? round($hasil->jumlah_tercapai / $hasil->jumlah_mahasiswa * 100)
                            : 0;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <span class="font-mono font-semibold text-violet-700 text-xs">{{ $hasil->kode_cpl }}</span>
                            @if ($hasil->cpl_deskripsi)
                                <p class="text-xs text-gray-400 mt-0.5 max-w-xs">{{ Str::limit($hasil->cpl_deskripsi, 60) }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $hasil->semester_nama }}</td>
                        <td class="px-5 py-3 text-right font-semibold {{ $tercapai ? 'text-green-700' : 'text-red-600' }}">
                            {{ number_format($hasil->nilai_rata_rata, 2) }}
                        </td>
                        <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ $hasil->jumlah_mahasiswa }}</td>
                        <td class="px-5 py-3 text-right text-xs">
                            <span class="{{ $tercapai ? 'text-green-700' : 'text-amber-600' }}">
                                {{ $hasil->jumlah_tercapai }}/{{ $hasil->jumlah_mahasiswa }}
                            </span>
                            <span class="text-gray-400 ml-1">({{ $pct }}%)</span>
                        </td>
                        <td class="px-5 py-3 text-right text-gray-400 text-xs">
                            {{ $hasil->batas_nilai !== null ? number_format($hasil->batas_nilai, 0) : '—' }}
                        </td>
                        <td class="px-5 py-3">
                            @if ($tercapai)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Tercapai</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">Belum</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="px-5 py-12 text-center text-gray-400 text-sm">
            Belum ada data hasil CPL untuk kurikulum ini.
        </div>
    @endif
</div>

@endsection
