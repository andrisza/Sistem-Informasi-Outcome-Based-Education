@extends('layouts.app')

@section('title', 'Detail Nilai — ' . ($mataKuliah->nama_mk ?? '-'))
@section('header', 'Detail Nilai')

@section('breadcrumb')
    <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('mahasiswa.nilai.index') }}" class="hover:text-blue-600">Nilai</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">{{ $mataKuliah->kode_mk ?? 'Detail' }}</span>
@endsection

@section('content')

<div class="max-w-4xl space-y-6">

    {{-- Header MK --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-mono text-gray-400 mb-1">{{ $mataKuliah->kode_mk }}</p>
                <h3 class="text-lg font-bold text-gray-900">{{ $mataKuliah->nama_mk }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">{{ $semesterAkademik->nama }} · {{ $mataKuliah->sks_total }} SKS</p>
            </div>
            @php
                $statusColor = match($enrollment->status) {
                    'aktif'       => 'bg-blue-100 text-blue-700',
                    'mengulang'   => 'bg-orange-100 text-orange-700',
                    'lulus'       => 'bg-green-100 text-green-700',
                    'tidak_lulus' => 'bg-red-100 text-red-700',
                    default       => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                    {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                </span>
                @if ($nilaiAkhir > 0)
                    <p class="text-3xl font-bold mt-2 {{ $nilaiAkhir >= 56 ? 'text-green-600' : 'text-red-500' }}">
                        {{ number_format($nilaiAkhir, 2) }}
                    </p>
                    <p class="text-xs text-gray-400">Nilai Akhir</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabel Komponen Asesmen --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h4 class="font-semibold text-gray-800 text-sm">Komponen Penilaian</h4>
        </div>

        @if ($komponenDenganNilai->isEmpty())
            <div class="px-5 py-8 text-center">
                <p class="text-sm text-gray-400">Belum ada komponen penilaian yang didefinisikan untuk mata kuliah ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Komponen</th>
                            <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bobot (%)</th>
                            <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Skor Maks</th>
                            <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Mentah</th>
                            <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($komponenDenganNilai as $item)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3 text-gray-800 font-medium">
                                    {{ $item['komponen']->nama_komponen }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 capitalize">
                                    {{ str_replace('_', ' ', $item['komponen']->jenis_komponen ?? '-') }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">
                                    {{ $item['komponen']->bobot_persen }}%
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">
                                    {{ $item['komponen']->skor_maks ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center {{ $item['nilai_mentah'] !== null ? 'text-gray-800 font-medium' : 'text-gray-400' }}">
                                    {{ $item['nilai_mentah'] !== null ? $item['nilai_mentah'] : '—' }}
                                </td>
                                <td class="px-4 py-3 text-center {{ $item['nilai_terboboti'] !== null ? 'text-amber-700 font-semibold' : 'text-gray-400' }}">
                                    {{ $item['nilai_terboboti'] !== null ? number_format($item['nilai_terboboti'], 2) : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200 bg-gray-50">
                            <td colspan="5" class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total Nilai Akhir</td>
                            <td class="px-4 py-3 text-center text-sm font-bold {{ $nilaiAkhir >= 56 ? 'text-green-600' : ($nilaiAkhir > 0 ? 'text-red-500' : 'text-gray-400') }}">
                                {{ $nilaiAkhir > 0 ? number_format($nilaiAkhir, 2) : '—' }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    {{-- Hasil CPMK --}}
    @if ($hasilCpmkList->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h4 class="font-semibold text-gray-800 text-sm">Capaian Per-CPMK</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode CPMK</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($hasilCpmkList as $hasilCpmk)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3 font-mono text-xs text-amber-700 font-semibold">
                                {{ $hasilCpmk->cpmk?->kode_cpmk ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $hasilCpmk->cpmk?->deskripsi ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center font-semibold {{ (float)$hasilCpmk->nilai >= 56 ? 'text-green-600' : 'text-red-500' }}">
                                {{ number_format((float) $hasilCpmk->nilai, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Navigasi kembali --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('mahasiswa.nilai.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 font-medium">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke daftar nilai
        </a>
        <span class="text-gray-200">|</span>
        <a href="{{ route('mahasiswa.rps.show', [$mataKuliah->id, $semesterAkademik->id]) }}"
           class="text-sm text-amber-600 hover:text-amber-700 font-medium hover:underline">
            Lihat RPS →
        </a>
    </div>

</div>

@endsection
