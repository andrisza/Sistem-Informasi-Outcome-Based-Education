@extends('layouts.app')

@section('title', 'Rekapitulasi Ketercapaian')
@section('header', 'Rekapitulasi Ketercapaian')

@section('breadcrumb')
    <a href="{{ route('kaprodi.reports.index') }}" class="hover:text-blue-600">Laporan</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Rekapitulasi</span>
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

{{-- Stat Summary --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-5 mb-6">
    @php
        $cards = [
            ['label' => 'Total Kurikulum', 'value' => $totalKurikulum, 'color' => 'blue'],
            ['label' => 'CPL Tercapai',    'value' => $cplTercapai,    'color' => 'green'],
            ['label' => 'CPL Belum Tercapai', 'value' => $cplBelumTercapai, 'color' => 'red'],
            ['label' => 'PL Tercapai',     'value' => $plTercapai,     'color' => 'emerald'],
        ];
        $colorMap = [
            'blue'    => 'bg-blue-50 text-blue-700',
            'green'   => 'bg-green-50 text-green-700',
            'red'     => 'bg-red-50 text-red-700',
            'emerald' => 'bg-emerald-50 text-emerald-700',
        ];
    @endphp
    @foreach ($cards as $card)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-2xl font-bold {{ $colorMap[$card['color']] }} rounded-lg inline-block px-2 py-0.5 mb-1">
                {{ $card['value'] }}
            </p>
            <p class="text-sm text-gray-600 font-medium mt-1">{{ $card['label'] }}</p>
        </div>
    @endforeach
</div>

{{-- Per-Kurikulum CPL Summary --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h4 class="font-semibold text-gray-800 text-sm">Ringkasan CPL per Kurikulum</h4>
    </div>
    @if (count($rekapCpl))
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kurikulum</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total CPL</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tercapai</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Belum</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Persentase</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($rekapCpl as $rekap)
                    @php
                        $pct = $rekap['total'] > 0 ? round($rekap['tercapai'] / $rekap['total'] * 100) : 0;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-gray-800">{{ $rekap['kurikulum']->nama_kurikulum }}</p>
                            <p class="text-xs text-gray-400">{{ $rekap['kurikulum']->kode }} · {{ $rekap['kurikulum']->tahun_mulai }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-right text-gray-600">{{ $rekap['total'] }}</td>
                        <td class="px-5 py-3.5 text-right text-green-700 font-semibold">{{ $rekap['tercapai'] }}</td>
                        <td class="px-5 py-3.5 text-right text-red-600 font-semibold">{{ $rekap['belum'] }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-100 rounded-full h-2 max-w-24">
                                    <div class="h-2 rounded-full {{ $pct >= 80 ? 'bg-green-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 shrink-0">{{ $pct }}%</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('kaprodi.reports.cpl', $rekap['kurikulum']) }}"
                               class="text-xs text-blue-600 hover:underline">Detail →</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="px-5 py-12 text-center text-gray-400 text-sm">Belum ada data rekapitulasi.</div>
    @endif
</div>

{{-- Per-Kurikulum PL Summary --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h4 class="font-semibold text-gray-800 text-sm">Ringkasan PL per Kurikulum</h4>
    </div>
    @if (count($rekapPl))
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kurikulum</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total PL</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tercapai</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Belum</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Persentase</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($rekapPl as $rekap)
                    @php
                        $pct = $rekap['total'] > 0 ? round($rekap['tercapai'] / $rekap['total'] * 100) : 0;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-gray-800">{{ $rekap['kurikulum']->nama_kurikulum }}</p>
                            <p class="text-xs text-gray-400">{{ $rekap['kurikulum']->kode }} · {{ $rekap['kurikulum']->tahun_mulai }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-right text-gray-600">{{ $rekap['total'] }}</td>
                        <td class="px-5 py-3.5 text-right text-green-700 font-semibold">{{ $rekap['tercapai'] }}</td>
                        <td class="px-5 py-3.5 text-right text-red-600 font-semibold">{{ $rekap['belum'] }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-100 rounded-full h-2 max-w-24">
                                    <div class="h-2 rounded-full {{ $pct >= 80 ? 'bg-green-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 shrink-0">{{ $pct }}%</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('kaprodi.reports.pl', $rekap['kurikulum']) }}"
                               class="text-xs text-violet-600 hover:underline">Detail →</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="px-5 py-12 text-center text-gray-400 text-sm">Belum ada data rekapitulasi PL.</div>
    @endif
</div>

@endsection
