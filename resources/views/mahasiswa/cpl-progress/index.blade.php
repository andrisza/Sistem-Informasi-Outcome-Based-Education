@extends('layouts.app')

@section('title', 'Progress CPL')
@section('header', 'Progress Capaian Pembelajaran Lulusan')

@section('breadcrumb')
    <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Progress CPL</span>
@endsection

@section('content')

{{-- Summary cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 bg-blue-100 text-blue-600">
            @include('layouts._icon', ['name' => 'chart-bar', 'class' => 'w-5 h-5'])
        </div>
        <div>
            <p class="text-2xl font-bold text-blue-700">{{ $totalCpl }}</p>
            <p class="text-sm font-medium text-gray-700 mt-0.5">Total CPL</p>
            <p class="text-xs text-gray-400">Capaian Pembelajaran Lulusan prodi</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 bg-green-100 text-green-600">
            @include('layouts._icon', ['name' => 'check-circle', 'class' => 'w-5 h-5'])
        </div>
        <div>
            <p class="text-2xl font-bold text-green-700">{{ $cplTercapai }}</p>
            <p class="text-sm font-medium text-gray-700 mt-0.5">CPL Tercapai</p>
            <p class="text-xs text-gray-400">Memenuhi batas minimal</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        @php
        $belumTercapai = $totalCpl - $cplTercapai;
        $pctCpl = $totalCpl > 0 ? round(($cplTercapai / $totalCpl) * 100) : 0;
        $statCplCls = $belumTercapai > 0 ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600';
        $statCplVal = $belumTercapai > 0 ? 'text-amber-700' : 'text-emerald-700';
    @endphp
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 {{ $statCplCls }}">
            @include('layouts._icon', ['name' => 'trending-up', 'class' => 'w-5 h-5'])
        </div>
        <div>
            <p class="text-2xl font-bold {{ $statCplVal }}">{{ $pctCpl }}%</p>
            <p class="text-sm font-medium text-gray-700 mt-0.5">Tingkat Ketercapaian</p>
            <p class="text-xs text-gray-400">{{ $belumTercapai }} CPL belum tercapai</p>
        </div>
    </div>
</div>

@if ($cplProgress->isNotEmpty())
{{-- Radar + Progress bars --}}
<div class="grid grid-cols-1 xl:grid-cols-5 gap-5 mb-6">

    {{-- Radar Chart --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                @include('layouts._icon', ['name' => 'radar', 'class' => 'w-4 h-4 text-blue-500'])
                <h3 class="font-semibold text-gray-800 text-sm">Radar CPL</h3>
            </div>
            <span class="text-xs text-gray-400">Spider Chart</span>
        </div>
        <div class="p-5">
            <div class="relative" style="height:280px">
                <canvas id="cpl-radar-full"></canvas>
            </div>
            <div class="flex items-center justify-center gap-4 mt-3">
                <span class="flex items-center gap-1.5 text-xs text-gray-500">
                    <span class="w-3 h-0.5 rounded bg-indigo-500 inline-block"></span> Nilai Saya
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
            <h3 class="font-semibold text-gray-800 text-sm">Progress per CPL</h3>
            <span class="text-xs text-gray-400">Hover kode untuk deskripsi</span>
        </div>
        <div class="px-5 py-4 space-y-3.5 overflow-y-auto" style="max-height:340px">
            @foreach ($cplProgress as $item)
                @php
                    $batas = $item['batas_nilai'] ?? 65;
                    $pct = $batas > 0 ? min(($item['nilai_rata'] / $batas) * 100, 100) : ($item['nilai_rata'] > 0 ? 100 : 0);
                    $barColor = $item['status_tercapai'] ? 'bg-green-500' : 'bg-amber-400';
                    $textColor = $item['status_tercapai'] ? 'text-green-700' : 'text-amber-600';
                @endphp
                <div class="group"
                     data-tooltip="{{ $item['cpl']?->kode_cpl }}: {{ $item['cpl']?->deskripsi ?? '' }}"
                     data-tip-label="CPL Prodi">
                    <div class="flex items-center justify-between mb-1.5 gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded shrink-0 cursor-help">
                                {{ $item['cpl']?->kode_cpl ?? '-' }}
                            </span>
                            <span class="text-xs text-gray-500 truncate">
                                {{ Str::limit($item['cpl']?->deskripsi ?? '-', 55) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-sm font-bold {{ $textColor }}">{{ $item['nilai_rata'] }}</span>
                            <span class="text-xs text-gray-400">/{{ $batas > 0 ? $batas : '—' }}</span>
                            @if ($item['status_tercapai'])
                                <svg class="w-3.5 h-3.5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-2 {{ $barColor }} rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-gray-400 w-8 text-right shrink-0">{{ round($pct) }}%</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endif

{{-- Detail table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 text-sm">Detail Progress Per CPL</h3>
        <span class="text-xs text-gray-400">Klik "Detail" untuk melihat breakdown per MK</span>
    </div>

    @if ($cplProgress->isEmpty())
        <div class="px-5 py-14 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="text-gray-500 font-medium">Belum ada data capaian CPL.</p>
            <p class="text-sm text-gray-400 mt-1">Data akan muncul setelah nilai diproses oleh sistem.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Kode CPL</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Batas</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Nilai</th>
                        <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-44">Progress</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Status</th>
                        <th class="text-center px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($cplProgress as $item)
                        @php
                            $batas = $item['batas_nilai'] ?? 65;
                            $pct = $batas > 0 ? min(($item['nilai_rata'] / $batas) * 100, 100) : ($item['nilai_rata'] > 0 ? 100 : 0);
                            $barColor = $item['status_tercapai'] ? 'bg-green-500' : 'bg-amber-400';
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3">
                                <span class="inline-block font-mono text-xs font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded cursor-help"
                                      data-tooltip="{{ $item['cpl']?->kode_cpl }}: {{ $item['cpl']?->deskripsi ?? '' }}"
                                      data-tip-label="CPL Prodi">
                                    {{ $item['cpl']?->kode_cpl ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-xs leading-relaxed max-w-xs">
                                {{ $item['cpl']?->deskripsi ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600 text-sm">
                                {{ $batas > 0 ? $batas : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center font-semibold {{ $item['status_tercapai'] ? 'text-green-700' : 'text-amber-600' }}">
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
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('mahasiswa.cpl-progress.show', $item['id_cpl']) }}"
                                   class="text-xs text-indigo-600 hover:text-indigo-700 font-medium hover:underline">
                                    Detail →
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const canvas = document.getElementById('cpl-radar-full');
    if (!canvas) return;

    const rawData = @json($cplProgress->values());
    if (!rawData.length) return;

    const labels  = rawData.map(d => d.cpl?.kode_cpl ?? '?');
    const values  = rawData.map(d => parseFloat(d.nilai_rata) || 0);
    const batas   = rawData.map(d => parseFloat(d.batas_nilai) || 65);

    new Chart(canvas, {
        type: 'radar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Nilai Saya',
                    data: values,
                    backgroundColor: 'rgba(99, 102, 241, 0.15)',
                    borderColor: 'rgba(99, 102, 241, 0.85)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(99, 102, 241, 0.9)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1.5,
                },
                {
                    label: 'Batas Minimal',
                    data: batas,
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
                        font: { size: 10, weight: '600' },
                        color: '#374151'
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
                            return rawData[idx]?.cpl?.kode_cpl ?? ctx[0].label;
                        },
                        label: ctx => {
                            if (ctx.dataset.label === 'Nilai Saya') {
                                const idx = ctx.dataIndex;
                                const d = rawData[idx];
                                const lines = [` Nilai: ${ctx.raw}`];
                                if (d?.cpl?.deskripsi) lines.push(` ${d.cpl.deskripsi.substring(0,55)}…`);
                                return lines;
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
