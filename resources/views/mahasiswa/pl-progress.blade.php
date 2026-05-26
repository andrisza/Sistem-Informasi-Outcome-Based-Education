@extends('layouts.app')

@section('title', 'Progress PL')
@section('header', 'Progress Profil Lulusan')

@section('breadcrumb')
    <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Progress PL</span>
@endsection

@section('content')

{{-- Summary cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 bg-violet-100 text-violet-600">
            @include('layouts._icon', ['name' => 'star', 'class' => 'w-5 h-5'])
        </div>
        <div>
            <p class="text-2xl font-bold text-violet-700">{{ $totalPl }}</p>
            <p class="text-sm font-medium text-gray-700 mt-0.5">Total Profil Lulusan</p>
            <p class="text-xs text-gray-400">Profil lulusan prodi</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 bg-green-100 text-green-600">
            @include('layouts._icon', ['name' => 'check-circle', 'class' => 'w-5 h-5'])
        </div>
        <div>
            <p class="text-2xl font-bold text-green-700">{{ $plTercapai }}</p>
            <p class="text-sm font-medium text-gray-700 mt-0.5">PL Tercapai</p>
            <p class="text-xs text-gray-400">Mencapai target minimal</p>
        </div>
    </div>
    @php
        $belumPl = $totalPl - $plTercapai;
        $pctPl   = $totalPl > 0 ? round(($plTercapai / $totalPl) * 100) : 0;
        $statPlIcon = $belumPl > 0 ? 'trending-up' : 'check-circle';
        $statPlCls  = $belumPl > 0
            ? 'bg-amber-100 text-amber-500'
            : 'bg-emerald-100 text-emerald-500';
        $statPlVal  = $belumPl > 0 ? 'text-amber-700' : 'text-emerald-700';
    @endphp
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 {{ $statPlCls }}">
            @include('layouts._icon', ['name' => $statPlIcon, 'class' => 'w-5 h-5'])
        </div>
        <div>
            <p class="text-2xl font-bold {{ $statPlVal }}">{{ $pctPl }}%</p>
            <p class="text-sm font-medium text-gray-700 mt-0.5">Tingkat Ketercapaian</p>
            <p class="text-xs text-gray-400">{{ $plTercapai }}/{{ $totalPl }} PL tercapai</p>
        </div>
    </div>
</div>

@if ($plProgress->isNotEmpty())
<div class="grid grid-cols-1 xl:grid-cols-5 gap-5 mb-6">

    {{-- Spider/Radar Chart --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                @include('layouts._icon', ['name' => 'radar', 'class' => 'w-4 h-4 text-violet-500'])
                <h3 class="font-semibold text-gray-800 text-sm">Radar Profil Lulusan</h3>
            </div>
            <span class="text-xs text-gray-400">Spider Chart</span>
        </div>
        <div class="p-5">
            <div class="relative" style="height:280px">
                <canvas id="pl-radar-chart"></canvas>
            </div>
            {{-- Legend --}}
            <div class="flex items-center justify-center gap-4 mt-3">
                <span class="flex items-center gap-1.5 text-xs text-gray-500">
                    <span class="w-3 h-0.5 rounded bg-violet-500 inline-block"></span> Nilai Saya
                </span>
                <span class="flex items-center gap-1.5 text-xs text-gray-500">
                    <span class="w-3 h-0.5 rounded border border-dashed border-red-400 inline-block"></span> Batas Minimal
                </span>
            </div>
        </div>
    </div>

    {{-- Progress bars --}}
    <div class="xl:col-span-3 bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Progress per Profil Lulusan</h3>
        </div>
        <div class="px-5 py-4 space-y-4">
            @foreach ($plProgress as $item)
                @php
                    $maxNilai = 100;
                    $pct = min(($item['nilai_rata'] / $maxNilai) * 100, 100);
                    $barColor = $item['status_tercapai'] ? 'bg-green-500' : 'bg-amber-400';
                    $textColor = $item['status_tercapai'] ? 'text-green-700' : 'text-amber-600';
                @endphp
                <div class="group"
                     data-tooltip="{{ $item['pl']?->kode_pl }}: {{ $item['pl']?->deskripsi ?? '' }}"
                     data-tip-label="Profil Lulusan">
                    <div class="flex items-start justify-between mb-1.5 gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="inline-block font-mono text-xs font-bold text-violet-700 bg-violet-50 px-2 py-0.5 rounded shrink-0">
                                {{ $item['pl']?->kode_pl ?? '-' }}
                            </span>
                            <span class="text-xs text-gray-600 truncate leading-snug" title="{{ $item['pl']?->deskripsi }}">
                                {{ Str::limit($item['pl']?->deskripsi ?? '-', 60) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-sm font-bold {{ $textColor }}">{{ $item['nilai_rata'] }}</span>
                            @if ($item['status_tercapai'])
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tercapai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                                    </svg>
                                    Dalam Proses
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-2.5 {{ $barColor }} rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-gray-400 w-8 text-right shrink-0">{{ round($pct) }}%</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endif

{{-- Tabel detail --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 text-sm">Detail Profil Lulusan</h3>
        <span class="text-xs text-gray-400">Hover pada kode untuk melihat deskripsi lengkap</span>
    </div>

    @if ($plProgress->isEmpty())
        <div class="px-5 py-14 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <p class="text-gray-500 font-medium">Belum ada data capaian PL.</p>
            <p class="text-sm text-gray-400 mt-1">Data akan muncul setelah nilai diproses oleh sistem.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Kode PL</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi Profil Lulusan</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Nilai</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">Progress</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($plProgress as $item)
                        @php
                            $maxNilai = 100;
                            $pct = min(($item['nilai_rata'] / $maxNilai) * 100, 100);
                            $barColor = $item['status_tercapai'] ? 'bg-green-500' : 'bg-amber-400';
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3">
                                <span class="inline-block font-mono text-xs font-semibold text-violet-700 bg-violet-50 px-2 py-0.5 rounded cursor-help"
                                      data-tooltip="{{ $item['pl']?->kode_pl }}: {{ $item['pl']?->deskripsi ?? '' }}"
                                      data-tip-label="Profil Lulusan">
                                    {{ $item['pl']?->kode_pl ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-xs leading-relaxed max-w-sm">
                                {{ $item['pl']?->deskripsi ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-base {{ $item['status_tercapai'] ? 'text-green-700' : 'text-amber-600' }}">
                                {{ $item['nilai_rata'] }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-2 {{ $barColor }} rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-8 text-right">{{ round($pct) }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($item['status_tercapai'])
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Tercapai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                                        </svg>
                                        Dalam Proses
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const canvas = document.getElementById('pl-radar-chart');
    if (!canvas) return;

    const rawData = @json($plProgress->values());
    if (!rawData.length) return;

    const labels  = rawData.map(d => d.pl?.kode_pl ?? '?');
    const values  = rawData.map(d => d.nilai_rata ?? 0);
    const targets = Array(labels.length).fill(65);

    new Chart(canvas, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Nilai Saya',
                    data: values,
                    backgroundColor: 'rgba(139, 92, 246, 0.15)',
                    borderColor: 'rgba(139, 92, 246, 0.85)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(139, 92, 246, 0.9)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1.5,
                },
                {
                    label: 'Batas Minimal (65)',
                    data: targets,
                    backgroundColor: 'rgba(239, 68, 68, 0.04)',
                    borderColor: 'rgba(239, 68, 68, 0.4)',
                    borderWidth: 1.5,
                    borderDash: [5, 4],
                    pointRadius: 0,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    min: 0,
                    max: 100,
                    ticks: {
                        stepSize: 25,
                        font: { size: 9 },
                        color: '#9ca3af',
                        backdropColor: 'transparent',
                    },
                    grid: { color: 'rgba(0,0,0,0.06)' },
                    angleLines: { color: 'rgba(0,0,0,0.07)' },
                    pointLabels: {
                        font: { size: 11, weight: '600' },
                        color: '#4c1d95'
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#94a3b8',
                    bodyColor: '#f1f5f9',
                    titleFont: { size: 10 },
                    bodyFont: { size: 12, weight: '700' },
                    padding: 10,
                    callbacks: {
                        title: ctx => {
                            const idx = ctx[0].dataIndex;
                            return rawData[idx]?.pl?.kode_pl ?? ctx[0].label;
                        },
                        label: ctx => {
                            if (ctx.dataset.label === 'Nilai Saya') {
                                const idx = ctx.dataIndex;
                                const d = rawData[idx];
                                const desc = d?.pl?.deskripsi ?? '';
                                return [` Nilai: ${ctx.raw}`, desc ? ` ${desc.substring(0,60)}…` : ''];
                            }
                            return ` ${ctx.dataset.label}: ${ctx.raw}`;
                        }
                    }
                }
            }
        }
    });
})();
</script>
@endpush
