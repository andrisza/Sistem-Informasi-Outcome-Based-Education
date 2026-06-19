@extends('layouts.app')

@section('title', 'Dashboard Kaprodi')
@section('header', 'Dashboard')

@section('content')

{{-- ── Stat cards ── --}}
<div class="grid grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
    @php
        $stats = [
            ['label' => 'Total Pengguna',  'value' => $totalUsers,     'sub' => 'aktif di sistem',      'color' => 'blue',   'icon' => 'users'],
            ['label' => 'Kurikulum Aktif', 'value' => $kurikulumAktif, 'sub' => 'periode berjalan',     'color' => 'violet', 'icon' => 'book-open'],
            ['label' => 'RPS Pending',     'value' => $rpsPending,     'sub' => 'menunggu persetujuan', 'color' => 'amber',  'icon' => 'document-check'],
        ];
        $colorMap = [
            'blue'   => ['icon' => 'bg-blue-100 text-blue-600',    'val' => 'text-blue-700'],
            'violet' => ['icon' => 'bg-violet-100 text-violet-600','val' => 'text-violet-700'],
            'amber'  => ['icon' => 'bg-amber-100 text-amber-600',  'val' => 'text-amber-700'],
        ];
    @endphp
    @foreach ($stats as $stat)
        @php $c = $colorMap[$stat['color']]; @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $c['icon'] }}">
                @include('layouts._icon', ['name' => $stat['icon'], 'class' => 'w-4 h-4'])
            </div>
            <div>
                <p class="text-xl font-bold {{ $c['val'] }}">{{ $stat['value'] }}</p>
                <p class="text-xs font-medium text-gray-700 mt-0.5 leading-tight">{{ $stat['label'] }}</p>
                <p class="text-xs text-gray-400">{{ $stat['sub'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- ── Radar Ketercapaian CPL ── --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">

    {{-- Card header --}}
    <div class="px-5 py-3.5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 leading-tight">Radar Ketercapaian CPL</h3>
                @if ($radarKurikulum && $radarSemester)
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $radarKurikulum->kode }} &mdash; {{ $radarSemester->nama }} {{ $radarSemester->tahun_akademik }}
                    @if ($radarSemester->is_aktif) <span class="text-violet-500 font-medium">(Aktif)</span> @endif
                </p>
                @endif
            </div>
        </div>

        {{-- Selector --}}
        <form method="GET" class="flex flex-wrap items-center gap-2">
            @if ($allKurikulum->count() > 1)
            <select name="radar_kurikulum" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs bg-gray-50 focus:outline-none focus:ring-2 focus:ring-violet-400">
                @foreach ($allKurikulum as $kur)
                    <option value="{{ $kur->id }}" {{ $radarKurikulum?->id == $kur->id ? 'selected' : '' }}>
                        {{ $kur->kode }} — {{ Str::limit($kur->nama_kurikulum, 28) }}
                    </option>
                @endforeach
            </select>
            @elseif ($radarKurikulum)
            <input type="hidden" name="radar_kurikulum" value="{{ $radarKurikulum->id }}">
            @endif
            <select name="radar_semester" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs bg-gray-50 focus:outline-none focus:ring-2 focus:ring-violet-400">
                @forelse ($semesterList as $smt)
                    <option value="{{ $smt->id }}" {{ $radarSemester?->id == $smt->id ? 'selected' : '' }}>
                        {{ $smt->nama }} {{ $smt->tahun_akademik }}{{ $smt->is_aktif ? ' ★' : '' }}
                    </option>
                @empty
                    <option value="">— Belum ada semester —</option>
                @endforelse
            </select>
        </form>
    </div>

    {{-- Body --}}
    <div class="p-5">
        @if (!$radarKurikulum)
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <svg class="w-10 h-10 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm">Belum ada kurikulum aktif.</p>
            </div>
        @elseif ($cplList->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <svg class="w-10 h-10 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m-9 3h18a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <p class="text-sm">Kurikulum <strong>{{ $radarKurikulum->kode }}</strong> belum memiliki CPL Prodi.</p>
            </div>
        @elseif (empty($angkatanDatasets) && empty($mahasiswaDatasets))
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <svg class="w-10 h-10 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
                <p class="text-sm font-medium">Belum ada data HasilCPL</p>
                <p class="text-xs mt-1">Data muncul setelah proses evaluasi CPL dilakukan untuk semester ini.</p>
            </div>
        @else

        @php
            $cplLabels    = $cplList->pluck('kode_cpl')->toArray();
            $cplDescs     = $cplList->pluck('deskripsi')->toArray();
            $plLabels     = $plList->pluck('kode_pl')->toArray();
            $plDescs      = $plList->pluck('deskripsi')->toArray();
            $totalMhs     = $mahasiswaList->count();
            $angkatanKeys = array_keys($angkatanDatasets);
            $hasPl        = $plList->isNotEmpty() && !empty($angkatanDatasetsPerPl);
        @endphp

        {{-- ── Header: tabs + CPL/PL mode toggle ── --}}
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;flex-wrap:wrap">
            <div style="display:flex;gap:4px;padding:4px;background:#f3f4f6;border-radius:8px">
                <button id="tab-angkatan" onclick="switchTab('angkatan')"
                        style="padding:6px 16px;font-size:12px;font-weight:600;border-radius:6px;background:white;color:#6d28d9;box-shadow:0 1px 2px rgba(0,0,0,.12);border:none;cursor:pointer">
                    Per Angkatan
                    <span style="margin-left:6px;background:#ede9fe;color:#7c3aed;font-size:10px;font-weight:700;padding:1px 6px;border-radius:9999px">{{ count($angkatanKeys) }}</span>
                </button>
                <button id="tab-individu" onclick="switchTab('individu')"
                        style="padding:6px 16px;font-size:12px;font-weight:600;border-radius:6px;background:transparent;color:#6b7280;border:none;cursor:pointer">
                    Per Individu
                    <span style="margin-left:6px;background:#e5e7eb;color:#6b7280;font-size:10px;font-weight:700;padding:1px 6px;border-radius:9999px">{{ $totalMhs }}</span>
                </button>
            </div>

            @if ($hasPl)
            <div style="margin-left:auto;display:flex;gap:4px;padding:4px;background:#f3f4f6;border-radius:8px">
                <button id="mode-cpl" onclick="switchMode('cpl')"
                        style="padding:5px 14px;font-size:11px;font-weight:600;border-radius:6px;background:white;color:#6d28d9;box-shadow:0 1px 2px rgba(0,0,0,.12);border:none;cursor:pointer">
                    Capaian CPL
                </button>
                <button id="mode-pl" onclick="switchMode('pl')"
                        style="padding:5px 14px;font-size:11px;font-weight:600;border-radius:6px;background:transparent;color:#6b7280;border:none;cursor:pointer">
                    Capaian PL
                </button>
            </div>
            @endif
        </div>

        {{-- ── Per Angkatan ── --}}
        <div id="panel-angkatan" style="display:flex;flex-direction:row;align-items:flex-start">

            {{-- Chart column (user-resizable) --}}
            <div id="angkatan-chart-col" style="flex:0 0 340px;min-width:180px;display:flex;flex-direction:column">
                {{-- Filter dropdown --}}
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
                    <span style="font-size:11px;color:#9ca3af;white-space:nowrap;flex-shrink:0">Filter:</span>
                    <select id="angk-filter-select"
                            onchange="filterAngkatan(this.value === '' ? null : this.value)"
                            style="flex:1;min-width:0;border:1px solid #ede9fe;border-radius:6px;padding:5px 28px 5px 10px;font-size:11px;font-weight:600;background:#faf5ff url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2210%22 height=%2210%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%236d28d9%22 stroke-width=%222.5%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E') no-repeat right 10px center;color:#6d28d9;cursor:pointer;outline:none;-webkit-appearance:none;appearance:none">
                        <option value="">Semua Angkatan</option>
                        @foreach ($angkatanKeys as $tahun)
                        <option value="{{ $tahun }}">Angkatan {{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="position:relative;height:300px;width:100%">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>

            {{-- Drag handle --}}
            <div id="angkatan-resize-handle"
                 style="flex-shrink:0;width:20px;cursor:col-resize;display:flex;align-items:center;justify-content:center;align-self:stretch"
                 title="Seret untuk mengubah ukuran">
                <div data-drag-bar style="width:3px;height:48px;background:#e5e7eb;border-radius:99px;transition:background .15s"
                     onmouseover="this.style.background='#a78bfa'" onmouseout="this.style.background='#e5e7eb'"></div>
            </div>

            {{-- Table column --}}
            <div style="flex:1;min-width:200px;overflow-x:auto">

                {{-- CPL table --}}
                <div id="angkatan-table-cpl" style="border-radius:8px;border:1px solid #f3f4f6;overflow:hidden">
                    <table style="width:100%;font-size:11px;border-collapse:collapse">
                        <thead>
                            <tr>
                                <th style="padding:8px 12px;text-align:left;font-weight:600;color:white;border-right:1px solid #6D28D9;background:#7C3AED;width:80px">CPL</th>
                                @foreach ($angkatanKeys as $tahun)
                                <th style="padding:8px 12px;text-align:center;font-weight:600;color:white;border-right:1px solid #6D28D9;background:#7C3AED;white-space:nowrap">Angk. {{ $tahun }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cplList as $i => $cpl)
                            <tr style="background:{{ $loop->even ? '#f5f3ff' : 'white' }};border-bottom:1px solid #f3f4f6">
                                <td style="padding:8px 12px;border-right:1px solid #f3f4f6;white-space:nowrap">
                                    <span title="{{ $cpl->deskripsi }}" style="font-family:monospace;font-weight:700;color:#6D28D9;font-size:11px;text-decoration:underline dotted;text-underline-offset:2px;cursor:help">{{ $cpl->kode_cpl }}</span>
                                </td>
                                @foreach ($angkatanDatasets as $tahun => $vals)
                                    @php $v = $vals[$i] ?? null; @endphp
                                    <td style="padding:10px 12px;text-align:center;border-right:1px solid #f3f4f6">
                                        @if ($v === null)
                                            <span style="color:#d1d5db;font-weight:600">—</span>
                                        @elseif ($v >= 70)
                                            <span style="color:#059669;font-weight:700">✓ {{ $v }}</span>
                                        @else
                                            <span style="color:#DC2626;font-weight:700">✗ {{ $v }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PL table (shown only in PL mode) --}}
                @if ($hasPl)
                <div id="angkatan-table-pl" style="display:none;border-radius:8px;border:1px solid #f3f4f6;overflow:hidden">
                    <table style="width:100%;font-size:11px;border-collapse:collapse">
                        <thead>
                            <tr>
                                <th style="padding:8px 12px;text-align:left;font-weight:600;color:white;border-right:1px solid #0d9488;background:#0f766e;width:80px">PL</th>
                                @foreach ($angkatanKeys as $tahun)
                                <th style="padding:8px 12px;text-align:center;font-weight:600;color:white;border-right:1px solid #0d9488;background:#0f766e;white-space:nowrap">Angk. {{ $tahun }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plList as $i => $pl)
                            <tr style="background:{{ $loop->even ? '#f0fdfa' : 'white' }};border-bottom:1px solid #f3f4f6">
                                <td style="padding:8px 12px;border-right:1px solid #f3f4f6;white-space:nowrap">
                                    <span title="{{ $pl->deskripsi }}" style="font-family:monospace;font-weight:700;color:#0f766e;font-size:11px;text-decoration:underline dotted;text-underline-offset:2px;cursor:help">{{ $pl->kode_pl }}</span>
                                </td>
                                @foreach ($angkatanDatasetsPerPl as $tahun => $vals)
                                    @php $v = $vals[$i] ?? null; @endphp
                                    <td style="padding:10px 12px;text-align:center;border-right:1px solid #f3f4f6">
                                        @if ($v === null)
                                            <span style="color:#d1d5db;font-weight:600">—</span>
                                        @elseif ($v >= 70)
                                            <span style="color:#059669;font-weight:700">✓ {{ $v }}</span>
                                        @else
                                            <span style="color:#DC2626;font-weight:700">✗ {{ $v }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Per Individu ── --}}
        <div id="panel-individu" style="display:none;flex-direction:row;align-items:flex-start">

            {{-- Left: student list --}}
            <div style="width:240px;flex-shrink:0;display:flex;flex-direction:column;gap:8px;margin-right:16px">
                <div style="position:relative">
                    <svg style="width:14px;height:14px;color:#9ca3af;position:absolute;left:10px;top:50%;transform:translateY(-50%);pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    <input id="mhs-search" type="text" placeholder="Cari nama atau NIM..."
                           style="width:100%;border:1px solid #e5e7eb;border-radius:8px;padding:7px 60px 7px 32px;font-size:12px;background:#f9fafb;outline:none;box-sizing:border-box"
                           oninput="filterMhs()">
                    <span id="search-count" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:10px;color:#9ca3af;font-weight:500;white-space:nowrap"></span>
                </div>
                <div id="mhs-list" style="display:flex;flex-direction:column;overflow-y:auto;border-radius:8px;border:1px solid #f3f4f6;max-height:380px">
                    @foreach ($mahasiswaList as $mhs)
                    <button type="button"
                            onclick="selectMhs({{ $mhs->id }})"
                            class="mhs-item"
                            style="text-align:left;width:100%;padding:9px 12px;display:flex;align-items:center;gap:10px;border-bottom:1px solid #f9fafb;background:white;cursor:pointer"
                            onmouseover="if(!this.classList.contains('mhs-active')) this.style.background='#f5f3ff'"
                            onmouseout="if(!this.classList.contains('mhs-active')) this.style.background='white'"
                            data-id="{{ $mhs->id }}"
                            data-name="{{ strtolower($mhs->name) }}"
                            data-nim="{{ strtolower($mhs->identifier) }}">
                        <div style="width:32px;height:32px;border-radius:50%;background:#ede9fe;color:#6d28d9;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;text-transform:uppercase">
                            {{ mb_substr($mhs->name, 0, 1) }}
                        </div>
                        <div style="min-width:0;flex:1">
                            <p style="font-size:12px;font-weight:500;color:#1f2937;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.2">{{ $mhs->name }}</p>
                            <p style="font-size:11px;color:#9ca3af;margin-top:2px;font-family:monospace">{{ $mhs->identifier }}{{ $mhs->tahun_masuk ? ' · ' . $mhs->tahun_masuk : '' }}</p>
                        </div>
                        <svg class="selected-icon" style="display:none;width:14px;height:14px;color:#7c3aed;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                    @endforeach
                    <div id="mhs-empty" style="display:none;padding:32px 12px;text-align:center;font-size:12px;color:#9ca3af">
                        Tidak ada mahasiswa yang cocok.
                    </div>
                </div>
                <p style="font-size:11px;color:#9ca3af;text-align:center">{{ $totalMhs }} mahasiswa terdaftar</p>
            </div>

            {{-- Middle: radar chart (user-resizable) --}}
            <div id="individu-chart-col" style="flex:0 0 320px;min-width:180px;display:flex;flex-direction:column;align-items:center">
                <div id="individu-placeholder" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:48px 16px;color:#9ca3af;width:100%">
                    <svg style="width:48px;height:48px;margin-bottom:12px;color:#e5e7eb" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <p style="font-size:13px;font-weight:500;margin:0">Pilih mahasiswa</p>
                    <p style="font-size:11px;margin:4px 0 0;color:#c4b5fd">Gunakan pencarian di kiri atau klik dari daftar</p>
                </div>
                <div id="radarChartIndividuWrap" style="position:relative;height:300px;width:100%;display:none">
                    <canvas id="radarChartIndividu"></canvas>
                </div>
                <div id="mhs-selected-info" style="display:none;margin-top:10px;text-align:center">
                    <p id="mhs-selected-name" style="font-size:13px;font-weight:600;color:#1f2937;margin:0"></p>
                    <p id="mhs-selected-nim" style="font-size:11px;color:#9ca3af;font-family:monospace;margin:2px 0 0"></p>
                </div>
            </div>

            {{-- Drag handle (visible when student selected) --}}
            <div id="individu-resize-handle"
                 style="flex-shrink:0;width:20px;cursor:col-resize;display:none;align-items:center;justify-content:center;align-self:stretch"
                 title="Seret untuk mengubah ukuran">
                <div data-drag-bar style="width:3px;height:48px;background:#e5e7eb;border-radius:99px;transition:background .15s"
                     onmouseover="this.style.background='#a78bfa'" onmouseout="this.style.background='#e5e7eb'"></div>
            </div>

            {{-- Right: detail table --}}
            <div id="individu-detail-wrap" style="display:none;flex:1;min-width:0">
                <div style="border-radius:8px;border:1px solid #f3f4f6;overflow:hidden">
                    <table style="width:100%;font-size:11px;border-collapse:collapse">
                        <thead>
                            <tr style="background:#7C3AED">
                                <th id="individu-th-label" style="padding:8px 12px;text-align:left;color:white;font-weight:600;border-right:1px solid #6D28D9;min-width:80px">CPL</th>
                                <th style="padding:8px 12px;text-align:center;color:white;font-weight:600;border-right:1px solid #6D28D9">Nilai</th>
                                <th style="padding:8px 12px;text-align:center;color:white;font-weight:600">Status</th>
                            </tr>
                        </thead>
                        <tbody id="individu-rows"></tbody>
                    </table>
                </div>
            </div>
        </div>

        @endif
    </div>
</div>

{{-- ── Main grid ── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

    {{-- RPS Pending --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm flex flex-col">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">RPS Menunggu Persetujuan</h3>
            <a href="{{ route('kaprodi.rps-approval.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Lihat semua →</a>
        </div>
        <div class="divide-y divide-gray-50 flex-1">
            @forelse ($rpsPendingList as $rps)
                <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50/60 transition-colors">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $rps->mataKuliah?->nama_mk ?? '—' }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <span class="font-mono text-gray-500">{{ $rps->mataKuliah?->kode_mk ?? '' }}</span>
                            · {{ $rps->dosenPengembang?->name ?? '—' }}
                            · {{ $rps->semester?->nama ?? '—' }}
                        </p>
                    </div>
                    <a href="{{ route('kaprodi.rps-approval.show', $rps) }}"
                       class="ml-4 shrink-0 text-xs bg-amber-100 text-amber-700 font-medium px-3 py-1.5 rounded-full hover:bg-amber-200 transition-colors">
                        Review
                    </a>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-sm text-gray-400">
                    <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tidak ada RPS yang menunggu persetujuan.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Aktivitas terbaru --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex flex-col">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Aktivitas Terbaru</h3>
            <a href="{{ route('kaprodi.activity-log.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Semua →</a>
        </div>
        <div class="px-5 py-3 space-y-3 flex-1 overflow-y-auto" style="max-height: 320px">
            @forelse ($recentActivity as $log)
                <div class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 text-xs font-bold uppercase">
                        {{ mb_substr($log->user?->name ?? '?', 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-gray-700 leading-snug">
                            <span class="font-medium">{{ $log->user?->name ?? 'System' }}</span>
                            {{ $log->action }}
                            <span class="text-gray-400">{{ class_basename($log->model_type) }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-6 text-center">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
@if (!empty($angkatanDatasets) || !empty($mahasiswaDatasets))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    // ── Data from PHP ───────────────────────────────────────────────────────
    const cplLabels  = @json($cplLabels);
    const cplDescs   = @json($cplDescs);
    const plLabels   = @json($plLabels);
    const plDescs    = @json($plDescs);
    const angCplData = @json($angkatanDatasets);
    const angPlData  = @json($angkatanDatasetsPerPl);
    const mhsData    = @json($mahasiswaDatasets);
    const hasPl      = @json($hasPl);

    const palette = [
        {r:124,g:58,b:237},
        {r:5,g:150,b:105},
        {r:217,g:119,b:6},
        {r:220,g:38,b:38},
        {r:59,g:130,b:246},
        {r:236,g:72,b:153},
        {r:20,g:184,b:166},
        {r:249,g:115,b:22},
    ];
    const rgba = (c, a) => `rgba(${c.r},${c.g},${c.b},${a})`;

    let currentMode = 'cpl';

    function makeThreshold(labels) {
        return {
            label: 'Batas Tercapai (70)',
            data: labels.map(() => 70),
            borderColor: 'rgba(239,68,68,0.45)',
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            borderDash: [5, 3],
            pointRadius: 0,
            order: 99,
        };
    }

    function makeOpts(labels, descs) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    min: 0, max: 100,
                    ticks: { stepSize: 25, font: { size: 10 }, backdropColor: 'transparent' },
                    pointLabels: { font: { size: 11, weight: '600' } },
                    grid: { color: 'rgba(0,0,0,0.06)' },
                    angleLines: { color: 'rgba(0,0,0,0.06)' },
                },
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: (items) => {
                            const i = items[0].dataIndex;
                            return `${labels[i]}: ${descs[i] || ''}`;
                        },
                        label: (item) => ` Nilai: ${item.raw}`,
                    },
                },
            },
        };
    }

    function buildAngkatanDS(dataObj, labelArr) {
        return Object.entries(dataObj).map(([tahun, vals], i) => {
            const c = palette[i % palette.length];
            return {
                label: 'Angkatan ' + tahun,
                data: labelArr.map((_, j) => (vals[j] ?? 0)),
                borderColor: rgba(c, 0.85),
                backgroundColor: rgba(c, 0.1),
                pointBackgroundColor: rgba(c, 0.85),
                borderWidth: 2,
                pointRadius: 3.5,
            };
        });
    }

    // Precompute angkatan datasets
    const angCplDS = buildAngkatanDS(angCplData, cplLabels);
    const angPlDS  = hasPl ? buildAngkatanDS(angPlData, plLabels) : [];

    // ── Angkatan chart init ─────────────────────────────────────────────────
    const ctxA   = document.getElementById('radarChart').getContext('2d');
    const chartA = new Chart(ctxA, {
        type: 'radar',
        data: { labels: cplLabels, datasets: [...angCplDS, makeThreshold(cplLabels)] },
        options: makeOpts(cplLabels, cplDescs),
    });

    // ── Individu chart init ─────────────────────────────────────────────────
    const ctxI   = document.getElementById('radarChartIndividu').getContext('2d');
    const chartI = new Chart(ctxI, {
        type: 'radar',
        data: { labels: cplLabels, datasets: [makeThreshold(cplLabels)] },
        options: makeOpts(cplLabels, cplDescs),
    });

    // ── CPL/PL mode switch ──────────────────────────────────────────────────
    window.switchMode = function (mode) {
        if (!hasPl) return;
        currentMode = mode;

        const btnCpl = document.getElementById('mode-cpl');
        const btnPl  = document.getElementById('mode-pl');
        const active   = 'background:white;color:#6d28d9;box-shadow:0 1px 2px rgba(0,0,0,.12)';
        const inactive = 'background:transparent;color:#6b7280';

        if (btnCpl) btnCpl.style.cssText = `padding:5px 14px;font-size:11px;font-weight:600;border-radius:6px;${mode==='cpl'?active:inactive};border:none;cursor:pointer`;
        if (btnPl)  btnPl.style.cssText  = `padding:5px 14px;font-size:11px;font-weight:600;border-radius:6px;${mode==='pl'?active:inactive};border:none;cursor:pointer`;

        // Switch angkatan chart + tables
        if (mode === 'cpl') {
            chartA.data.labels   = cplLabels;
            chartA.data.datasets = [...angCplDS, makeThreshold(cplLabels)];
            chartA.options       = makeOpts(cplLabels, cplDescs);
            const tblCpl = document.getElementById('angkatan-table-cpl');
            const tblPl  = document.getElementById('angkatan-table-pl');
            if (tblCpl) tblCpl.style.display = '';
            if (tblPl)  tblPl.style.display  = 'none';
        } else {
            chartA.data.labels   = plLabels;
            chartA.data.datasets = [...angPlDS, makeThreshold(plLabels)];
            chartA.options       = makeOpts(plLabels, plDescs);
            const tblCpl = document.getElementById('angkatan-table-cpl');
            const tblPl  = document.getElementById('angkatan-table-pl');
            if (tblCpl) tblCpl.style.display = 'none';
            if (tblPl)  tblPl.style.display  = '';
        }
        // Reset angkatan filter to "Semua" on mode switch
        const sel = document.getElementById('angk-filter-select');
        if (sel) sel.value = '';
        chartA.update();

        // Re-render individu chart if someone is selected
        if (selectedId !== null) renderIndividuChart(selectedId);
    };

    // ── Filter angkatan (select dropdown) ──────────────────────────────────
    window.filterAngkatan = function (tahun) {
        const sel = document.getElementById('angk-filter-select');
        if (sel) sel.value = tahun === null ? '' : tahun;

        const dataObj = currentMode === 'cpl' ? angCplData : angPlData;
        const DS      = currentMode === 'cpl' ? angCplDS : angPlDS;
        const labels  = currentMode === 'cpl' ? cplLabels : plLabels;
        chartA.data.datasets = tahun === null
            ? [...DS, makeThreshold(labels)]
            : [DS[Object.keys(dataObj).indexOf(tahun)], makeThreshold(labels)];
        chartA.update();
    };

    // ── Tab switch ──────────────────────────────────────────────────────────
    window.switchTab = function (tab) {
        const tabA = document.getElementById('tab-angkatan');
        const tabI = document.getElementById('tab-individu');
        const panA = document.getElementById('panel-angkatan');
        const panI = document.getElementById('panel-individu');

        const activeTab   = 'padding:6px 16px;font-size:12px;font-weight:600;border-radius:6px;background:white;color:#6d28d9;box-shadow:0 1px 2px rgba(0,0,0,.12);border:none;cursor:pointer';
        const inactiveTab = 'padding:6px 16px;font-size:12px;font-weight:600;border-radius:6px;background:transparent;color:#6b7280;border:none;cursor:pointer';

        if (tab === 'angkatan') {
            tabA.style.cssText = activeTab;
            tabI.style.cssText = inactiveTab;
            panA.style.display = 'flex';
            panI.style.display = 'none';
        } else {
            tabI.style.cssText = activeTab;
            tabA.style.cssText = inactiveTab;
            panI.style.display = 'flex';
            panA.style.display = 'none';
        }
    };

    // ── Mahasiswa search ────────────────────────────────────────────────────
    window.filterMhs = function () {
        const q     = document.getElementById('mhs-search').value.toLowerCase().trim();
        const items = document.querySelectorAll('.mhs-item');
        let visible = 0;

        items.forEach(el => {
            const match = !q || el.dataset.name.includes(q) || el.dataset.nim.includes(q);
            el.style.display = match ? 'flex' : 'none';
            if (match) visible++;
        });

        document.getElementById('search-count').textContent = q ? `${visible} hasil` : '';
        document.getElementById('mhs-empty').style.display = visible > 0 ? 'none' : 'block';
    };

    // ── Render individu radar ───────────────────────────────────────────────
    function renderIndividuChart(id) {
        const mhs = mhsData[id];
        if (!mhs) return;

        const usePl   = currentMode === 'pl' && hasPl && mhs.pl_values;
        const labels  = usePl ? plLabels  : cplLabels;
        const descs   = usePl ? plDescs   : cplDescs;
        const values  = usePl ? (mhs.pl_values || []) : mhs.values;
        const c = palette[0];

        chartI.data.labels   = labels;
        chartI.data.datasets = [
            {
                label: mhs.name,
                data: labels.map((_, i) => (values[i] ?? 0)),
                borderColor: rgba(c, 0.85),
                backgroundColor: rgba(c, 0.12),
                pointBackgroundColor: rgba(c, 0.85),
                borderWidth: 2,
                pointRadius: 3.5,
            },
            makeThreshold(labels),
        ];
        chartI.options = makeOpts(labels, descs);
        chartI.update();

        // Update detail table
        const thLabel = document.getElementById('individu-th-label');
        if (thLabel) thLabel.textContent = usePl ? 'PL' : 'CPL';

        const thColor = usePl ? '#0f766e' : '#7C3AED';
        const thBorder = usePl ? '#0d9488' : '#6D28D9';
        const thEl = document.querySelector('#individu-detail-wrap table thead tr');
        if (thEl) thEl.style.background = thColor;

        const tbody = document.getElementById('individu-rows');
        tbody.innerHTML = '';
        labels.forEach((kode, i) => {
            const val  = values[i];
            const ok   = val !== null && val >= 70;
            const color = val === null ? '#d1d5db' : (ok ? '#059669' : '#DC2626');
            const badge = val === null
                ? '<span style="color:#d1d5db">—</span>'
                : ok
                    ? '<span style="display:inline-flex;align-items:center;background:#ecfdf5;color:#059669;font-size:10px;font-weight:600;padding:2px 6px;border-radius:4px">✓ Tercapai</span>'
                    : '<span style="display:inline-flex;align-items:center;background:#fef2f2;color:#DC2626;font-size:10px;font-weight:600;padding:2px 6px;border-radius:4px">✗ Belum</span>';
            const tr = document.createElement('tr');
            tr.style.background = i % 2 === 0 ? (usePl ? '#f0fdfa' : '#f5f3ff') : 'white';
            tr.innerHTML = `
                <td style="padding:8px 12px;border-bottom:1px solid #f3f4f6;border-right:1px solid #f3f4f6;font-family:monospace;font-weight:700;color:${thColor};font-size:11px">${kode}</td>
                <td style="padding:8px 12px;border-bottom:1px solid #f3f4f6;border-right:1px solid #f3f4f6;text-align:center;font-weight:700;font-size:12px;color:${color}">${val !== null ? val : '—'}</td>
                <td style="padding:8px 12px;border-bottom:1px solid #f3f4f6;text-align:center">${badge}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    // ── Select mahasiswa ────────────────────────────────────────────────────
    let selectedId = null;

    window.selectMhs = function (id) {
        selectedId = id;

        document.querySelectorAll('.mhs-item').forEach(el => {
            const isThis = parseInt(el.dataset.id) === id;
            el.style.background = isThis ? '#f5f3ff' : 'white';
            el.classList.toggle('mhs-active', isThis);
            el.querySelector('.selected-icon').style.display = isThis ? 'block' : 'none';
        });

        const mhs = mhsData[id];
        if (!mhs) return;

        renderIndividuChart(id);

        document.getElementById('individu-placeholder').style.display   = 'none';
        document.getElementById('radarChartIndividuWrap').style.display  = 'block';
        document.getElementById('mhs-selected-info').style.display       = 'block';
        document.getElementById('mhs-selected-name').textContent         = mhs.name;
        document.getElementById('mhs-selected-nim').textContent          = mhs.identifier + (mhs.angkatan ? ' · Angk. ' + mhs.angkatan : '');
        document.getElementById('individu-detail-wrap').style.display    = 'block';
        document.getElementById('individu-resize-handle').style.display  = 'flex';
        chartI.resize();
    };

    // ── Resizable panels ────────────────────────────────────────────────────
    function initResize(handleId, colId, storageKey, getChart, minW, maxW) {
        const handle = document.getElementById(handleId);
        const col    = document.getElementById(colId);
        if (!handle || !col) return;

        const saved = localStorage.getItem(storageKey);
        if (saved) col.style.flex = '0 0 ' + saved + 'px';

        const bar = handle.querySelector('[data-drag-bar]');

        handle.addEventListener('mousedown', function (e) {
            e.preventDefault();
            const startX = e.clientX;
            const startW = col.getBoundingClientRect().width;
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none';
            if (bar) bar.style.background = '#7c3aed';

            function onMove(e2) {
                const w = Math.max(minW, Math.min(maxW, startW + (e2.clientX - startX)));
                col.style.flex = '0 0 ' + w + 'px';
                const chart = getChart();
                if (chart) chart.resize();
            }

            function onUp() {
                document.body.style.cursor = '';
                document.body.style.userSelect = '';
                if (bar) bar.style.background = '#e5e7eb';
                localStorage.setItem(storageKey, Math.round(col.getBoundingClientRect().width));
                document.removeEventListener('mousemove', onMove);
                document.removeEventListener('mouseup', onUp);
            }

            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
        });
    }

    initResize('angkatan-resize-handle', 'angkatan-chart-col', 'obe-radar-ang-w', () => chartA, 200, 650);
    initResize('individu-resize-handle', 'individu-chart-col', 'obe-radar-ind-w', () => chartI, 200, 600);
})();
</script>
@endif
@endpush
