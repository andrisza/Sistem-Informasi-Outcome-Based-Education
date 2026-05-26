@extends('layouts.app')

@section('title', 'Detail CPL — ' . ($cplProdi->kode_cpl ?? '-'))
@section('header', 'Detail Capaian CPL')

@section('breadcrumb')
    <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('mahasiswa.cpl-progress.index') }}" class="hover:text-blue-600">Progress CPL</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $cplProdi->kode_cpl }}</span>
@endsection

@section('content')

<div class="max-w-4xl space-y-6">

    {{-- Info CPL --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-block font-mono text-sm font-bold text-amber-700 bg-amber-50 px-3 py-1 rounded-lg">
                        {{ $cplProdi->kode_cpl }}
                    </span>
                    @if ($cplProdi->kategori ?? false)
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $cplProdi->kategori }}</span>
                    @endif
                </div>
                <p class="text-gray-700 leading-relaxed">{{ $cplProdi->deskripsi }}</p>
                @if ($cplProdi->kurikulum)
                    <p class="text-xs text-gray-400 mt-2">Kurikulum: {{ $cplProdi->kurikulum->nama_kurikulum }}</p>
                @endif
            </div>
            <div class="text-right shrink-0">
                @if ($tercapai)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tercapai
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-amber-100 text-amber-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Belum Tercapai
                    </span>
                @endif
            </div>
        </div>

        {{-- Progress bar nilai vs batas --}}
        @php
            $batasNilai = $batas?->batas_nilai ?? 0;
            $nilaiAvg   = round((float) $nilaiRata, 2);
            $pct = $batasNilai > 0
                ? min(($nilaiAvg / $batasNilai) * 100, 100)
                : ($nilaiAvg > 0 ? 100 : 0);
            $barColor = $tercapai ? 'bg-green-500' : 'bg-amber-400';
        @endphp
        <div class="mt-5 pt-5 border-t border-gray-100">
            <div class="flex items-end justify-between mb-2">
                <div>
                    <p class="text-xs text-gray-500">Nilai Rata-rata</p>
                    <p class="text-3xl font-bold {{ $tercapai ? 'text-green-600' : 'text-amber-600' }}">
                        {{ $nilaiAvg }}
                    </p>
                </div>
                @if ($batasNilai > 0)
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Batas Ketercapaian</p>
                        <p class="text-lg font-semibold text-gray-700">{{ $batasNilai }}</p>
                    </div>
                @endif
            </div>
            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-3 {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1 text-right">{{ round($pct) }}% dari batas ketercapaian</p>
        </div>
    </div>

    {{-- Nilai per Semester --}}
    @if ($hasilPerSemester->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h4 class="font-semibold text-gray-800 text-sm">Nilai CPL per Semester</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai CPL</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($hasilPerSemester as $hasil)
                        @php
                            $pctSmt = $batasNilai > 0
                                ? min(((float)$hasil->nilai_cpl / $batasNilai) * 100, 100)
                                : ((float)$hasil->nilai_cpl > 0 ? 100 : 0);
                            $barSmt = $hasil->status_tercapai ? 'bg-green-500' : 'bg-amber-400';
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3 text-gray-700">{{ $hasil->semester?->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-center font-semibold {{ $hasil->status_tercapai ? 'text-green-700' : 'text-amber-600' }}">
                                {{ number_format((float)$hasil->nilai_cpl, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($hasil->status_tercapai)
                                    <span class="text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full">Tercapai</span>
                                @else
                                    <span class="text-xs font-medium text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">Belum</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-2 {{ $barSmt }} rounded-full" style="width: {{ $pctSmt }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-8 text-right">{{ round($pctSmt) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- CPMK yang berkontribusi --}}
    @if ($hasilCpmkList->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h4 class="font-semibold text-gray-800 text-sm">CPMK yang Memetakan ke CPL Ini</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode CPMK</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($hasilCpmkList as $hasilCpmk)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs font-semibold text-amber-700">
                                    {{ $hasilCpmk->cpmk?->kode_cpmk ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-xs max-w-xs">
                                {{ $hasilCpmk->cpmk?->deskripsi ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $hasilCpmk->cpmk?->mataKuliah?->nama_mk ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                {{ $hasilCpmk->semester?->nama ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center font-semibold {{ (float)$hasilCpmk->nilai >= 56 ? 'text-green-600' : 'text-amber-600' }}">
                                {{ number_format((float)$hasilCpmk->nilai, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Navigasi kembali --}}
    <a href="{{ route('mahasiswa.cpl-progress.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 font-medium">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke daftar CPL
    </a>

</div>

@endsection
