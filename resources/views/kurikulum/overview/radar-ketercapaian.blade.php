@extends('layouts.app')

@section('title', 'Radar Ketercapaian CPL – ' . $kurikulum->kode)
@section('header', 'Radar Ketercapaian CPL')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Radar Ketercapaian CPL</span>
@endsection

@section('content')

@php
    $cplLabels    = $cplList->pluck('kode_cpl')->toArray();
    $cplLabelJson = json_encode($cplLabels);
    $individuJson = json_encode($cplIndividuData);
    $angkatanJson = json_encode($cplAngkatanData);
@endphp

{{-- Semester selector --}}
<form method="GET" class="flex flex-wrap items-end gap-3 mb-5">
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Semester Akademik:</label>
        <select name="semester" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
            @forelse ($semesterList as $smt)
                <option value="{{ $smt->id }}" {{ $semester && $semester->id === $smt->id ? 'selected' : '' }}>
                    {{ $smt->nama }} {{ $smt->tahun_akademik }} {{ $smt->is_aktif ? '(Aktif)' : '' }}
                </option>
            @empty
                <option value="">— Belum ada semester —</option>
            @endforelse
        </select>
    </div>
    {{-- Keep existing selections when changing semester --}}
    @if ($selectedMhsId)
        <input type="hidden" name="mahasiswa" value="{{ $selectedMhsId }}">
    @endif
    @if ($selectedAngkatan)
        <input type="hidden" name="angkatan" value="{{ $selectedAngkatan }}">
    @endif
</form>

@if ($cplList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        CPL Prodi belum diisi untuk kurikulum ini.
    </div>
@else

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- ── Kiri: Per Mahasiswa ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">Ketercapaian CPL per Mahasiswa</h3>
            <p class="text-xs text-gray-400 mt-0.5">Pilih mahasiswa untuk melihat radar capaian CPL-nya</p>
        </div>
        <div class="px-5 py-4 space-y-4">
            {{-- Mahasiswa selector --}}
            <form method="GET" id="form-individu">
                <input type="hidden" name="semester" value="{{ $semester?->id }}">
                @if ($selectedAngkatan)
                    <input type="hidden" name="angkatan" value="{{ $selectedAngkatan }}">
                @endif
                <label class="block text-xs font-medium text-gray-600 mb-1">Mahasiswa:</label>
                <select name="mahasiswa" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Pilih Mahasiswa —</option>
                    @foreach ($mahasiswaList as $mhs)
                        <option value="{{ $mhs->id }}" {{ $selectedMhsId === $mhs->id ? 'selected' : '' }}>
                            {{ $mhs->name }} ({{ $mhs->identifier ?? '-' }})
                            @if ($mhs->tahun_masuk) — Angkatan {{ $mhs->tahun_masuk }} @endif
                        </option>
                    @endforeach
                </select>
            </form>

            @if ($selectedMhs && !empty($cplIndividuData))
                {{-- Radar chart --}}
                <div style="height:380px;position:relative">
                    <canvas id="radar-individu"></canvas>
                </div>

                {{-- Data table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-3 py-2 text-left font-semibold text-gray-600 border border-gray-200">CPL</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600 border border-gray-200">Deskripsi</th>
                                <th class="px-3 py-2 text-center font-semibold text-gray-600 border border-gray-200">Capaian</th>
                                <th class="px-3 py-2 text-center font-semibold text-gray-600 border border-gray-200">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cplList as $idx => $cpl)
                                @php $val = $cplIndividuData[$idx] ?? 0; @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border border-gray-200 font-mono font-semibold text-gray-700">{{ $cpl->kode_cpl }}</td>
                                    <td class="px-3 py-2 border border-gray-200 text-gray-600">{{ Str::limit($cpl->deskripsi, 60) }}</td>
                                    <td class="px-3 py-2 border border-gray-200 text-center font-semibold {{ $val >= 70 ? 'text-emerald-700' : 'text-red-600' }}">
                                        {{ $val }}
                                    </td>
                                    <td class="px-3 py-2 border border-gray-200 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $val >= 70 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $val >= 70 ? 'Tercapai' : 'Belum' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif ($selectedMhs)
                <div class="py-8 text-center text-sm text-gray-400">
                    Belum ada data capaian CPL untuk mahasiswa ini pada semester yang dipilih.
                </div>
            @else
                <div class="py-8 text-center text-sm text-gray-400">
                    Pilih mahasiswa untuk melihat radar ketercapaian CPL.
                </div>
            @endif
        </div>
    </div>

    {{-- ── Kanan: Per Angkatan ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">Ketercapaian CPL per Angkatan</h3>
            <p class="text-xs text-gray-400 mt-0.5">Rata-rata capaian CPL seluruh mahasiswa dalam satu angkatan</p>
        </div>
        <div class="px-5 py-4 space-y-4">
            {{-- Angkatan selector --}}
            <form method="GET" id="form-angkatan">
                <input type="hidden" name="semester" value="{{ $semester?->id }}">
                @if ($selectedMhsId)
                    <input type="hidden" name="mahasiswa" value="{{ $selectedMhsId }}">
                @endif
                <label class="block text-xs font-medium text-gray-600 mb-1">Angkatan:</label>
                <select name="angkatan" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Pilih Angkatan —</option>
                    @foreach ($angkatanList as $thn)
                        <option value="{{ $thn }}" {{ $selectedAngkatan === $thn ? 'selected' : '' }}>
                            Angkatan {{ $thn }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if ($selectedAngkatan && !empty($cplAngkatanData))
                {{-- Radar chart --}}
                <div style="height:380px;position:relative">
                    <canvas id="radar-angkatan"></canvas>
                </div>

                {{-- Data table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-3 py-2 text-left font-semibold text-gray-600 border border-gray-200">CPL</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600 border border-gray-200">Deskripsi</th>
                                <th class="px-3 py-2 text-center font-semibold text-gray-600 border border-gray-200">Rata-rata</th>
                                <th class="px-3 py-2 text-center font-semibold text-gray-600 border border-gray-200">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cplList as $idx => $cpl)
                                @php $val = $cplAngkatanData[$idx] ?? 0; @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border border-gray-200 font-mono font-semibold text-gray-700">{{ $cpl->kode_cpl }}</td>
                                    <td class="px-3 py-2 border border-gray-200 text-gray-600">{{ Str::limit($cpl->deskripsi, 60) }}</td>
                                    <td class="px-3 py-2 border border-gray-200 text-center font-semibold {{ $val >= 70 ? 'text-emerald-700' : 'text-red-600' }}">
                                        {{ $val }}
                                    </td>
                                    <td class="px-3 py-2 border border-gray-200 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $val >= 70 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $val >= 70 ? 'Tercapai' : 'Belum' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif ($selectedAngkatan)
                <div class="py-8 text-center text-sm text-gray-400">
                    Belum ada data capaian CPL untuk angkatan ini pada semester yang dipilih.
                </div>
            @else
                <div class="py-8 text-center text-sm text-gray-400">
                    Pilih angkatan untuk melihat radar rata-rata ketercapaian CPL.
                </div>
            @endif
        </div>
    </div>

</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    var labels    = {!! $cplLabelJson !!};
    var threshold = new Array(labels.length).fill(70);

    var radarDefaults = {
        type: 'radar',
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    min: 0,
                    max: 100,
                    ticks: { stepSize: 20, font: { size: 10 } },
                    pointLabels: { font: { size: 11, weight: '600' } },
                    grid: { color: 'rgba(0,0,0,0.07)' },
                    angleLines: { color: 'rgba(0,0,0,0.1)' },
                }
            },
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return ctx.dataset.label + ': ' + ctx.raw;
                        }
                    }
                }
            }
        }
    };

    // ── Radar Individu ────────────────────────────────────────────────────────
    var individu = {!! $individuJson !!};
    if (individu.length > 0 && document.getElementById('radar-individu')) {
        new Chart(document.getElementById('radar-individu'), Object.assign({}, radarDefaults, {
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Capaian CPL',
                        data: individu,
                        backgroundColor: 'rgba(59,130,246,0.15)',
                        borderColor: 'rgba(59,130,246,0.9)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(59,130,246,1)',
                        pointRadius: 4,
                    },
                    {
                        label: 'Threshold (70)',
                        data: threshold,
                        backgroundColor: 'rgba(239,68,68,0.05)',
                        borderColor: 'rgba(239,68,68,0.5)',
                        borderWidth: 1.5,
                        borderDash: [5, 4],
                        pointRadius: 0,
                    }
                ]
            }
        }));
    }

    // ── Radar Angkatan ────────────────────────────────────────────────────────
    var angkatan = {!! $angkatanJson !!};
    if (angkatan.length > 0 && document.getElementById('radar-angkatan')) {
        new Chart(document.getElementById('radar-angkatan'), Object.assign({}, radarDefaults, {
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Rata-rata Capaian CPL',
                        data: angkatan,
                        backgroundColor: 'rgba(16,185,129,0.15)',
                        borderColor: 'rgba(16,185,129,0.9)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(16,185,129,1)',
                        pointRadius: 4,
                    },
                    {
                        label: 'Threshold (70)',
                        data: threshold,
                        backgroundColor: 'rgba(239,68,68,0.05)',
                        borderColor: 'rgba(239,68,68,0.5)',
                        borderWidth: 1.5,
                        borderDash: [5, 4],
                        pointRadius: 0,
                    }
                ]
            }
        }));
    }
})();
</script>
@endpush
