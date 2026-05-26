@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('header', 'Dashboard')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start gap-3">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-amber-100 text-amber-600">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xl font-bold text-amber-700">{{ $enrollmentAktif->count() }}</p>
            <p class="text-xs font-medium text-gray-700 mt-0.5">MK Semester Ini</p>
            <p class="text-xs text-gray-400 truncate">{{ $semesterAktif?->nama ?? '—' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start gap-3">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-blue-100 text-blue-600">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xl font-bold text-blue-700">{{ $sksTotal }}</p>
            <p class="text-xs font-medium text-gray-700 mt-0.5">SKS Semester Ini</p>
            <p class="text-xs text-gray-400">Total kredit aktif</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start gap-3">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-green-100 text-green-600">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xl font-bold text-green-700">{{ $cplTercapai }}<span class="text-xs font-normal text-gray-400">/{{ $totalCpl }}</span></p>
            <p class="text-xs font-medium text-gray-700 mt-0.5">CPL Tercapai</p>
            <p class="text-xs text-gray-400">Capaian Pembelajaran</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start gap-3">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-violet-100 text-violet-600">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xl font-bold text-violet-700">{{ $totalMkDiikuti }}</p>
            <p class="text-xs font-medium text-gray-700 mt-0.5">Total MK Ditempuh</p>
            <p class="text-xs text-gray-400">Semua semester</p>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Tabel MK Semester Aktif --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">
                Mata Kuliah Semester Ini
                @if ($semesterAktif)
                    <span class="ml-1 text-xs text-gray-400 font-normal">— {{ $semesterAktif->nama }}</span>
                @endif
            </h3>
            <a href="{{ route('mahasiswa.nilai.index') }}"
               class="text-xs text-amber-600 hover:underline font-medium">Lihat semua nilai →</a>
        </div>

        @if ($enrollmentAktif->isEmpty())
            <div class="px-5 py-10 text-center text-sm text-gray-400">
                Belum ada mata kuliah yang diikuti semester ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">MK</th>
                            <th class="text-center px-3 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">SKS</th>
                            <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosen</th>
                            <th class="text-center px-3 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-center px-3 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($enrollmentAktif as $enroll)
                            @php
                                $dosenPengampu = $dosenMap->get($enroll->id_mk);
                                $namaDosenList = $dosenPengampu
                                    ? $dosenPengampu->map(fn($p) => $p->dosen?->name ?? '-')->implode(', ')
                                    : '-';
                                $statusColor = match($enroll->status) {
                                    'aktif'       => 'bg-blue-100 text-blue-700',
                                    'mengulang'   => 'bg-orange-100 text-orange-700',
                                    'lulus'       => 'bg-green-100 text-green-700',
                                    'tidak_lulus' => 'bg-red-100 text-red-700',
                                    default       => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800 text-sm">{{ $enroll->mataKuliah?->nama_mk ?? '-' }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $enroll->mataKuliah?->kode_mk ?? '-' }}</p>
                                </td>
                                <td class="px-3 py-3 text-center text-gray-600 text-sm">
                                    {{ $enroll->mataKuliah?->sks_total ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs max-w-[140px] truncate">
                                    {{ $namaDosenList }}
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $enroll->status)) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($semesterAktif)
                                            <a href="{{ route('mahasiswa.nilai.show', [$enroll->id_mk, $enroll->id_semester]) }}"
                                               class="text-xs text-amber-600 hover:text-amber-700 font-medium hover:underline">Nilai</a>
                                            <span class="text-gray-200">|</span>
                                            <a href="{{ route('mahasiswa.rps.show', [$enroll->id_mk, $enroll->id_semester]) }}"
                                               class="text-xs text-blue-600 hover:text-blue-700 font-medium hover:underline">RPS</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Progress CPL + Spider Chart --}}
    <div class="space-y-5">

        {{-- Radar Chart CPL --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Radar CPL</h3>
                <a href="{{ route('mahasiswa.cpl-progress.index') }}"
                   class="text-xs text-amber-600 hover:underline font-medium">Detail →</a>
            </div>
            <div class="p-4">
                @if ($cplRadar->isEmpty())
                    <div class="py-8 text-center text-sm text-gray-400">
                        Belum ada data CPL.
                    </div>
                @else
                    <div class="relative" style="height:220px">
                        <canvas id="cpl-radar-chart"></canvas>
                    </div>
                @endif
            </div>
        </div>

        {{-- Progress bar ringkasan --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Progress CPL</h3>
            </div>
            @if ($cplSummary->isEmpty())
                <div class="px-5 py-8 text-center text-sm text-gray-400">
                    Belum ada data capaian CPL.<br>
                    <span class="text-xs text-gray-300">Data muncul setelah nilai diproses.</span>
                </div>
            @else
                <div class="px-5 py-4 space-y-3.5">
                    @foreach ($cplSummary as $item)
                        @php
                            $batas = $item['batas_nilai'] ?? 65;
                            $pct = $batas > 0
                                ? min(($item['nilai'] / $batas) * 100, 100)
                                : ($item['nilai'] > 0 ? 100 : 0);
                            $barColor = $item['status_tercapai'] ? 'bg-green-500' : 'bg-amber-400';
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold text-gray-700">{{ $item['kode_cpl'] }}</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs text-gray-500">{{ $item['nilai'] }}</span>
                                    @if ($item['status_tercapai'])
                                        <span class="text-xs text-green-600 font-medium">✓</span>
                                    @else
                                        <span class="text-xs text-amber-600 font-medium">—</span>
                                    @endif
                                </div>
                            </div>
                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-1.5 {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $item['deskripsi'] }}</p>
                        </div>
                    @endforeach
                    @if ($totalCpl > 5)
                        <p class="text-xs text-gray-400 text-center pt-1">+{{ $totalCpl - 5 }} CPL lainnya</p>
                    @endif
                </div>
            @endif
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const canvas = document.getElementById('cpl-radar-chart');
    if (!canvas) return;

    const labels  = @json($cplRadar->pluck('kode_cpl'));
    const values  = @json($cplRadar->pluck('nilai'));
    const targets = Array(labels.length).fill(65);

    new Chart(canvas, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Nilai Saya',
                    data: values,
                    backgroundColor: 'rgba(99, 102, 241, 0.15)',
                    borderColor: 'rgba(99, 102, 241, 0.8)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(99, 102, 241, 0.9)',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                },
                {
                    label: 'Batas Minimal',
                    data: targets,
                    backgroundColor: 'rgba(239, 68, 68, 0.05)',
                    borderColor: 'rgba(239, 68, 68, 0.35)',
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
                    ticks: { stepSize: 25, font: { size: 9 }, color: '#9ca3af' },
                    grid: { color: 'rgba(0,0,0,0.06)' },
                    angleLines: { color: 'rgba(0,0,0,0.06)' },
                    pointLabels: { font: { size: 10, weight: '600' }, color: '#374151' }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 10 }, boxWidth: 12, padding: 10 }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${ctx.raw}`
                    }
                }
            }
        }
    });
})();
</script>
@endpush
