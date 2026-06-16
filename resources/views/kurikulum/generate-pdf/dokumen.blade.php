<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumen Kurikulum – {{ $kurikulum->kode }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11pt; color: #1a1a1a; background: #fff; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; font-size: 10pt; }
            .page-break { page-break-before: always; break-before: page; }
            .avoid-break { page-break-inside: avoid; break-inside: avoid; }
        }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #9ca3af; padding: 5px 8px; font-size: 9.5pt; vertical-align: top; }
        th { text-align: center; font-weight: bold; }
        td { vertical-align: middle; }
        .th-dark  { background: #1e3a5f; color: #fff; }
        .th-blue  { background: #1d4ed8; color: #fff; }
        .th-green { background: #15803d; color: #fff; }
        .th-amber { background: #b45309; color: #fff; }
        .th-violet{ background: #6d28d9; color: #fff; }
        .th-teal  { background: #0f766e; color: #fff; }
        .th-light { background: #e5e7eb; color: #111827; }
        .check    { text-align: center; font-weight: bold; color: #15803d; font-size: 11pt; }
        .section-title { font-size: 13pt; font-weight: bold; color: #1e3a5f; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 5px; margin: 0 0 14px; }
        .sub-title { font-size: 11pt; font-weight: bold; color: #374151; margin: 12px 0 5px; }
        .badge-draft { display:inline-block; background:#f3f4f6; color:#6b7280; font-size:8.5pt; padding:1px 6px; border-radius:4px; font-weight:600; }
        .badge-ok    { display:inline-block; background:#dcfce7; color:#15803d; font-size:8.5pt; padding:1px 6px; border-radius:4px; font-weight:600; }
    </style>
</head>
<body class="bg-white">

{{-- ── Toolbar (tidak tercetak) ── --}}
<div class="no-print" style="position:sticky;top:0;z-index:50;background:#1e3a5f;padding:10px 20px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 6px rgba(0,0,0,.3)">
    <span style="color:#93c5fd;font-size:12px;font-weight:600;">Dokumen Kurikulum</span>
    <span style="color:#fff;font-size:13px;font-weight:bold;">{{ $kurikulum->kode }} — {{ $kurikulum->nama_kurikulum }}</span>
    <div style="margin-left:auto;display:flex;gap:8px;align-items:center">
        <button onclick="window.print()"
                style="background:#2563eb;color:#fff;font-size:12px;font-weight:600;padding:6px 16px;border-radius:7px;border:none;cursor:pointer;display:flex;align-items:center;gap:5px">
            <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak / Simpan PDF
        </button>
        <a href="{{ url()->previous() }}"
           style="background:#374151;color:#d1d5db;font-size:12px;padding:6px 14px;border-radius:7px;text-decoration:none">← Kembali</a>
    </div>
</div>

<div style="max-width:900px;margin:0 auto;padding:40px 50px 60px">

    {{-- ════════════════════════════════════
         HALAMAN JUDUL
         ════════════════════════════════════ --}}
    <div style="text-align:center;padding:40px 0 50px;border-bottom:3px solid #1e3a5f;margin-bottom:30px">
        <p style="font-size:10pt;text-transform:uppercase;letter-spacing:.12em;color:#6b7280;margin-bottom:6px">Dokumen Kurikulum Program Studi</p>
        <h1 style="font-size:22pt;font-weight:900;color:#1e3a5f;margin:0 0 6px">{{ $kurikulum->nama_kurikulum }}</h1>
        @if ($kurikulum->program_studi)
        <p style="font-size:13pt;color:#374151;margin:4px 0">{{ $kurikulum->program_studi }}
            @if ($kurikulum->jenjang) &nbsp;({{ $kurikulum->jenjang }})@endif
        </p>
        @endif
        <p style="font-size:11pt;color:#6b7280;margin:8px 0 0">
            Tahun {{ $kurikulum->tahun_mulai }}{{ $kurikulum->tahun_selesai ? '–' . $kurikulum->tahun_selesai : ' – sekarang' }}
        </p>
        <p style="font-size:9.5pt;color:#9ca3af;margin-top:6px">
            Kode: <strong>{{ $kurikulum->kode }}</strong>
            &nbsp;|&nbsp;
            Status: <strong>{{ ucfirst($kurikulum->status ?? 'aktif') }}</strong>
            &nbsp;|&nbsp;
            Dicetak: {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>

    {{-- ════════════════════════════════════
         VISI, MISI, TUJUAN
         ════════════════════════════════════ --}}
    @if ($kurikulum->visi || $kurikulum->misi || $kurikulum->tujuan)
    <div class="avoid-break" style="margin-bottom:30px">
        <h2 class="section-title">Visi, Misi, dan Tujuan Program Studi</h2>

        @if ($kurikulum->visi)
        <div class="avoid-break" style="margin-bottom:12px">
            <p class="sub-title">Visi</p>
            <p style="margin:0;line-height:1.7;font-style:italic;color:#374151">{{ $kurikulum->visi }}</p>
        </div>
        @endif

        @if ($kurikulum->misi)
        <div class="avoid-break" style="margin-bottom:12px">
            <p class="sub-title">Misi</p>
            <p style="margin:0;line-height:1.7;color:#374151;white-space:pre-line">{{ $kurikulum->misi }}</p>
        </div>
        @endif

        @if ($kurikulum->tujuan)
        <div class="avoid-break">
            <p class="sub-title">Tujuan</p>
            <p style="margin:0;line-height:1.7;color:#374151;white-space:pre-line">{{ $kurikulum->tujuan }}</p>
        </div>
        @endif
    </div>
    @endif

    {{-- ════════════════════════════════════
         1. PROFIL LULUSAN (PL)
         ════════════════════════════════════ --}}
    <div class="page-break avoid-break" style="margin-bottom:30px">
        <h2 class="section-title">1. Profil Lulusan (PL)</h2>
        <table>
            <thead>
                <tr>
                    <th class="th-dark" style="width:5%">No</th>
                    <th class="th-dark" style="width:12%">Kode PL</th>
                    <th class="th-dark" style="width:55%">Deskripsi Profil Lulusan</th>
                    <th class="th-dark" style="width:18%">Kategori</th>
                    <th class="th-dark" style="width:10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kurikulum->pl as $pl)
                <tr style="background:{{ $loop->odd ? '#fff' : '#f8fafc' }}">
                    <td style="text-align:center">{{ $pl->urutan }}</td>
                    <td style="text-align:center;font-family:monospace;font-weight:bold;color:#92400e">{{ $pl->kode_pl }}</td>
                    <td style="line-height:1.5">{{ $pl->deskripsi }}</td>
                    <td style="text-align:center;font-size:9pt">{{ $pl->kategori ?? '—' }}</td>
                    <td style="text-align:center">
                        @if (($pl->status ?? 'draft') === 'approved')
                            <span class="badge-ok">Approved</span>
                        @else
                            <span class="badge-draft">Draft</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#9ca3af;padding:16px">Belum ada Profil Lulusan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ════════════════════════════════════
         2. CPL PROGRAM STUDI
         ════════════════════════════════════ --}}
    <div class="avoid-break" style="margin-bottom:30px">
        <h2 class="section-title">2. Capaian Pembelajaran Lulusan (CPL) Program Studi</h2>
        <table>
            <thead>
                <tr>
                    <th class="th-blue" style="width:5%">No</th>
                    <th class="th-blue" style="width:12%">Kode CPL</th>
                    <th class="th-blue" style="width:63%">Rumusan CPL</th>
                    <th class="th-blue" style="width:20%">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kurikulum->cplProdi as $cpl)
                <tr style="background:{{ $loop->odd ? '#fff' : '#eff6ff' }}">
                    <td style="text-align:center">{{ $cpl->urutan }}</td>
                    <td style="text-align:center;font-family:monospace;font-weight:bold;color:#1d4ed8">{{ $cpl->kode_cpl }}</td>
                    <td style="line-height:1.5">{{ $cpl->deskripsi }}</td>
                    <td style="text-align:center;font-size:9pt">{{ $cpl->kategori ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:#9ca3af;padding:16px">Belum ada CPL.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ════════════════════════════════════
         3. BAHAN KAJIAN
         ════════════════════════════════════ --}}
    @if ($kurikulum->bahanKajian->count() > 0)
    <div class="page-break avoid-break" style="margin-bottom:30px">
        <h2 class="section-title">3. Bahan Kajian (BK)</h2>
        <table>
            <thead>
                <tr>
                    <th class="th-green" style="width:5%">No</th>
                    <th class="th-green" style="width:12%">Kode BK</th>
                    <th class="th-green" style="width:40%">Nama Bahan Kajian</th>
                    <th class="th-green" style="width:43%">Bidang Keilmuan / Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kurikulum->bahanKajian as $bk)
                <tr style="background:{{ $loop->odd ? '#fff' : '#f0fdf4' }}">
                    <td style="text-align:center">{{ $loop->iteration }}</td>
                    <td style="text-align:center;font-family:monospace;font-weight:bold;color:#15803d">{{ $bk->kode_bk }}</td>
                    <td>{{ $bk->nama_bk }}</td>
                    <td style="color:#374151;font-size:9pt">{{ $bk->bidang_keilmuan ?: ($bk->deskripsi ?: '—') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ════════════════════════════════════
         4. MATRIKS PL ↔ CPL
         ════════════════════════════════════ --}}
    @if ($kurikulum->pl->count() > 0 && $kurikulum->cplProdi->count() > 0 && $pivotPlCpl->count() > 0)
    <div class="page-break avoid-break" style="margin-bottom:30px">
        <h2 class="section-title">4. Matriks Pemetaan PL terhadap CPL</h2>
        <p style="font-size:9pt;color:#6b7280;margin-bottom:8px">Tanda <strong>✓</strong> menunjukkan CPL yang mendukung pencapaian Profil Lulusan tersebut.</p>
        <div style="overflow-x:auto">
        <table style="font-size:8.5pt">
            <thead>
                <tr>
                    <th class="th-dark" rowspan="2" style="width:12%">Kode PL</th>
                    <th class="th-dark" colspan="{{ $kurikulum->cplProdi->count() }}" style="text-align:center">CPL Program Studi</th>
                </tr>
                <tr>
                    @foreach ($kurikulum->cplProdi as $cpl)
                    <th class="th-blue" style="width:{{ max(6, 60 / max($kurikulum->cplProdi->count(),1)) }}%;font-size:8pt">{{ $cpl->kode_cpl }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($kurikulum->pl as $pl)
                <tr style="background:{{ $loop->odd ? '#fff' : '#f8fafc' }}">
                    <td style="font-family:monospace;font-weight:bold;color:#92400e;text-align:center">{{ $pl->kode_pl }}</td>
                    @foreach ($kurikulum->cplProdi as $cpl)
                    <td class="{{ in_array($cpl->id, $pivotPlCpl->get($pl->id, [])) ? 'check' : '' }}" style="text-align:center">
                        {{ in_array($cpl->id, $pivotPlCpl->get($pl->id, [])) ? '✓' : '' }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════
         5. MATRIKS CPL ↔ BAHAN KAJIAN
         ════════════════════════════════════ --}}
    @if ($kurikulum->bahanKajian->count() > 0 && $pivotCplBk->count() > 0)
    <div class="page-break avoid-break" style="margin-bottom:30px">
        <h2 class="section-title">5. Matriks Pemetaan CPL terhadap Bahan Kajian</h2>
        <p style="font-size:9pt;color:#6b7280;margin-bottom:8px">Tanda <strong>✓</strong> menunjukkan Bahan Kajian yang mendukung pencapaian CPL tersebut.</p>
        <div style="overflow-x:auto">
        <table style="font-size:8.5pt">
            <thead>
                <tr>
                    <th class="th-dark" rowspan="2" style="width:12%">Kode CPL</th>
                    <th class="th-green" colspan="{{ $kurikulum->bahanKajian->count() }}" style="text-align:center">Bahan Kajian</th>
                </tr>
                <tr>
                    @foreach ($kurikulum->bahanKajian as $bk)
                    <th class="th-green" style="font-size:8pt">{{ $bk->kode_bk }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($kurikulum->cplProdi as $cpl)
                <tr style="background:{{ $loop->odd ? '#fff' : '#f0fdf4' }}">
                    <td style="font-family:monospace;font-weight:bold;color:#1d4ed8;text-align:center">{{ $cpl->kode_cpl }}</td>
                    @foreach ($kurikulum->bahanKajian as $bk)
                    <td class="{{ in_array($bk->id, $pivotCplBk->get($cpl->id, [])) ? 'check' : '' }}" style="text-align:center">
                        {{ in_array($bk->id, $pivotCplBk->get($cpl->id, [])) ? '✓' : '' }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════
         6. MATA KULIAH PER SEMESTER
         ════════════════════════════════════ --}}
    <div class="page-break" style="margin-bottom:30px">
        <h2 class="section-title">6. Struktur Mata Kuliah per Semester</h2>

        @php
            $totalSksMk    = $kurikulum->mataKuliah->sum(fn ($mk) => ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0));
            $totalMk       = $kurikulum->mataKuliah->count();
        @endphp
        <p style="font-size:9.5pt;color:#374151;margin-bottom:10px">
            Total: <strong>{{ $totalMk }} mata kuliah</strong> &nbsp;|&nbsp; Total SKS: <strong>{{ $totalSksMk }}</strong>
        </p>

        @forelse ($mkBySemester as $smt => $mks)
        @php
            $sksSmt = $mks->sum(fn ($mk) => ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0));
        @endphp
        <div class="avoid-break" style="margin-bottom:14px">
            <p class="sub-title">Semester {{ $smt }}
                <span style="font-weight:normal;font-size:9.5pt;color:#6b7280">
                    ({{ $mks->count() }} MK &nbsp;|&nbsp; {{ $sksSmt }} SKS)
                </span>
            </p>
            <table style="font-size:9pt">
                <thead>
                    <tr>
                        <th class="th-dark" style="width:4%">No</th>
                        <th class="th-dark" style="width:13%">Kode MK</th>
                        <th class="th-dark" style="width:37%">Nama Mata Kuliah</th>
                        <th class="th-dark" style="width:7%">T</th>
                        <th class="th-dark" style="width:7%">P</th>
                        <th class="th-dark" style="width:7%">SKS</th>
                        <th class="th-dark" style="width:14%">Kategori</th>
                        <th class="th-dark" style="width:11%">Prasyarat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mks as $mk)
                    @php $sksMk = ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0); @endphp
                    <tr style="background:{{ $loop->odd ? '#fff' : '#f8fafc' }}">
                        <td style="text-align:center">{{ $loop->iteration }}</td>
                        <td style="font-family:monospace;font-weight:bold;text-align:center">{{ $mk->kode_mk }}</td>
                        <td>{{ $mk->nama_mk }}</td>
                        <td style="text-align:center">{{ $mk->sks_teori ?? 0 }}</td>
                        <td style="text-align:center">{{ $mk->sks_praktikum ?? 0 }}</td>
                        <td style="text-align:center;font-weight:bold">{{ $sksMk }}</td>
                        <td style="text-align:center;font-size:8.5pt">{{ $mk->kategori_mk ?? '—' }}</td>
                        <td style="text-align:center;font-size:8.5pt">{{ $mk->kode_prasyarat ?? '—' }}</td>
                    </tr>
                    @endforeach
                    <tr style="background:#e5e7eb">
                        <td colspan="5" style="text-align:right;font-weight:bold;font-size:9pt">Total SKS Semester {{ $smt }}</td>
                        <td style="text-align:center;font-weight:bold;font-size:10pt">{{ $sksSmt }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @empty
        <p style="color:#9ca3af;text-align:center;padding:20px">Belum ada mata kuliah terdaftar.</p>
        @endforelse
    </div>

    {{-- ════════════════════════════════════
         7. MATRIKS MK ↔ CPL
         ════════════════════════════════════ --}}
    @if ($kurikulum->mataKuliah->count() > 0 && $kurikulum->cplProdi->count() > 0 && $pivotMkCpl->count() > 0)
    <div class="page-break avoid-break" style="margin-bottom:30px">
        <h2 class="section-title">7. Matriks Pemetaan Mata Kuliah terhadap CPL</h2>
        <p style="font-size:9pt;color:#6b7280;margin-bottom:8px">Tanda <strong>✓</strong> menunjukkan CPL yang diemban oleh mata kuliah tersebut.</p>
        <div style="overflow-x:auto">
        <table style="font-size:8.5pt">
            <thead>
                <tr>
                    <th class="th-dark" rowspan="2" style="width:8%">Smt</th>
                    <th class="th-dark" rowspan="2" style="width:13%">Kode MK</th>
                    <th class="th-dark" rowspan="2" style="width:30%">Nama MK</th>
                    <th class="th-blue" colspan="{{ $kurikulum->cplProdi->count() }}" style="text-align:center">CPL Program Studi</th>
                </tr>
                <tr>
                    @foreach ($kurikulum->cplProdi as $cpl)
                    <th class="th-blue" style="font-size:8pt">{{ $cpl->kode_cpl }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($mkBySemester as $smt => $mks)
                    @foreach ($mks as $mk)
                    <tr style="background:{{ $loop->parent->odd ? ($loop->odd ? '#fff' : '#f8fafc') : ($loop->odd ? '#eff6ff' : '#dbeafe') }}">
                        @if ($loop->first)
                        <td style="text-align:center;font-weight:bold" rowspan="{{ $mks->count() }}">{{ $smt }}</td>
                        @endif
                        <td style="font-family:monospace;font-weight:bold;text-align:center;font-size:8pt">{{ $mk->kode_mk }}</td>
                        <td style="font-size:8.5pt">{{ $mk->nama_mk }}</td>
                        @foreach ($kurikulum->cplProdi as $cpl)
                        <td class="{{ in_array($cpl->id, $pivotMkCpl->get($mk->id, [])) ? 'check' : '' }}" style="text-align:center">
                            {{ in_array($cpl->id, $pivotMkCpl->get($mk->id, [])) ? '✓' : '' }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════
         8. CPMK DAN SUB-CPMK PER MK
         ════════════════════════════════════ --}}
    <div class="page-break" style="margin-bottom:30px">
        <h2 class="section-title">8. Capaian Pembelajaran Mata Kuliah (CPMK) dan Sub-CPMK</h2>

        @foreach ($kurikulum->mataKuliah as $mk)
            @if ($cpmkByMk->has($mk->id))
            @php $cpmks = $cpmkByMk[$mk->id]; @endphp
            <div class="avoid-break" style="margin-bottom:16px">
                <p class="sub-title">{{ $mk->kode_mk }} – {{ $mk->nama_mk }}
                    <span style="font-weight:normal;font-size:9pt;color:#6b7280">
                        (Semester {{ $mk->semester }}, {{ ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0) }} SKS)
                    </span>
                </p>
                <table style="font-size:8.5pt">
                    <thead>
                        <tr>
                            <th class="th-amber" style="width:12%">Kode CPMK</th>
                            <th class="th-amber" style="width:45%">Rumusan CPMK</th>
                            <th class="th-amber" style="width:10%">CPL</th>
                            <th class="th-amber" style="width:13%">Level Bloom</th>
                            <th class="th-amber" style="width:10%">Kode Sub-CPMK</th>
                            <th class="th-amber" style="width:10%">Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cpmks as $cpmk)
                            @if ($cpmk->subCpmk->isEmpty())
                            <tr style="background:{{ $loop->odd ? '#fff' : '#fffbeb' }}">
                                <td style="font-family:monospace;font-weight:bold;text-align:center;color:#92400e">{{ $cpmk->kode_cpmk }}</td>
                                <td style="line-height:1.5">{{ $cpmk->deskripsi }}</td>
                                <td style="text-align:center;font-family:monospace;color:#1d4ed8;font-weight:bold;font-size:8pt">{{ $cpmk->cplProdi?->kode_cpl ?? '—' }}</td>
                                <td style="text-align:center;font-size:8.5pt">{{ $cpmk->level_bloom ?? '—' }}</td>
                                <td colspan="2" style="text-align:center;color:#9ca3af;font-size:8.5pt">—</td>
                            </tr>
                            @else
                                @foreach ($cpmk->subCpmk as $sub)
                                <tr style="background:{{ $loop->parent->odd ? ($loop->odd ? '#fff' : '#fffbeb') : ($loop->odd ? '#fef9ee' : '#fef3c7') }}">
                                    @if ($loop->first)
                                    <td style="font-family:monospace;font-weight:bold;text-align:center;color:#92400e;vertical-align:middle" rowspan="{{ $cpmk->subCpmk->count() }}">{{ $cpmk->kode_cpmk }}</td>
                                    <td style="line-height:1.5;vertical-align:middle" rowspan="{{ $cpmk->subCpmk->count() }}">{{ $cpmk->deskripsi }}</td>
                                    <td style="text-align:center;font-family:monospace;color:#1d4ed8;font-weight:bold;font-size:8pt;vertical-align:middle" rowspan="{{ $cpmk->subCpmk->count() }}">{{ $cpmk->cplProdi?->kode_cpl ?? '—' }}</td>
                                    <td style="text-align:center;font-size:8.5pt;vertical-align:middle" rowspan="{{ $cpmk->subCpmk->count() }}">{{ $cpmk->level_bloom ?? '—' }}</td>
                                    @endif
                                    <td style="font-family:monospace;text-align:center;font-size:8pt;font-weight:bold">{{ $sub->kode_sub_cpmk }}</td>
                                    <td style="text-align:center;font-size:8.5pt">{{ $sub->bobot ? $sub->bobot . '%' : '—' }}</td>
                                </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        @endforeach
    </div>

    {{-- ════════════════════════════════════
         9. TEKNIK DAN BOBOT PENILAIAN
         ════════════════════════════════════ --}}
    @php
        $hasPenilaian = $cpmkByMk->flatten()->filter(fn ($c) => $c->penilaian)->count() > 0;
    @endphp
    @if ($hasPenilaian)
    <div class="page-break" style="margin-bottom:30px">
        <h2 class="section-title">9. Teknik dan Bobot Penilaian per CPMK</h2>
        <table style="font-size:8.5pt">
            <thead>
                <tr>
                    <th class="th-violet" style="width:10%">MK</th>
                    <th class="th-violet" style="width:10%">CPMK</th>
                    <th class="th-violet" style="width:10%">CPL</th>
                    <th class="th-violet" style="width:8%">Quiz</th>
                    <th class="th-violet" style="width:9%">Observasi</th>
                    <th class="th-violet" style="width:10%">Unjuk Kerja</th>
                    <th class="th-violet" style="width:7%">UTS</th>
                    <th class="th-violet" style="width:7%">UAS</th>
                    <th class="th-violet" style="width:9%">Tes Lisan</th>
                    <th class="th-violet" style="width:10%">Skor Maks</th>
                    <th class="th-violet" style="width:10%">Instrumen</th>
                </tr>
            </thead>
            <tbody>
                @php $rowIdx = 0; @endphp
                @foreach ($kurikulum->mataKuliah as $mk)
                    @if (!$cpmkByMk->has($mk->id)) @continue @endif
                    @foreach ($cpmkByMk[$mk->id] as $cpmk)
                    @php $p = $cpmk->penilaian; $rowIdx++; @endphp
                    <tr style="background:{{ $rowIdx % 2 === 0 ? '#f5f3ff' : '#fff' }}">
                        <td style="font-family:monospace;font-weight:bold;text-align:center;font-size:8pt">{{ $mk->kode_mk }}</td>
                        <td style="font-family:monospace;font-weight:bold;text-align:center;color:#6d28d9;font-size:8pt">{{ $cpmk->kode_cpmk }}</td>
                        <td style="font-family:monospace;text-align:center;color:#1d4ed8;font-size:8pt">{{ $cpmk->cplProdi?->kode_cpl ?? '—' }}</td>
                        <td style="text-align:center">{{ $p && $p->teknik_quiz ? ($p->bobot_quiz ? $p->bobot_quiz.'%' : '✓') : '' }}</td>
                        <td style="text-align:center">{{ $p && $p->teknik_observasi ? ($p->bobot_observasi ? $p->bobot_observasi.'%' : '✓') : '' }}</td>
                        <td style="text-align:center">{{ $p && $p->teknik_unjuk_kerja ? ($p->bobot_unjuk_kerja ? $p->bobot_unjuk_kerja.'%' : '✓') : '' }}</td>
                        <td style="text-align:center">{{ $p && $p->teknik_uts ? ($p->bobot_uts ? $p->bobot_uts.'%' : '✓') : '' }}</td>
                        <td style="text-align:center">{{ $p && $p->teknik_uas ? ($p->bobot_uas ? $p->bobot_uas.'%' : '✓') : '' }}</td>
                        <td style="text-align:center">{{ $p && $p->teknik_tes_lisan ? ($p->bobot_tes_lisan ? $p->bobot_tes_lisan.'%' : '✓') : '' }}</td>
                        <td style="text-align:center">{{ $p && !is_null($p->skor_maks) ? $p->skor_maks : '—' }}</td>
                        <td style="font-size:8pt">{{ $p?->instrumen ?: '—' }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ════════════════════════════════════
         FOOTER
         ════════════════════════════════════ --}}
    <div style="border-top:2px solid #e5e7eb;padding-top:12px;margin-top:20px;text-align:center;font-size:8.5pt;color:#9ca3af">
        <strong style="color:#6b7280">{{ $kurikulum->kode }}</strong> — {{ $kurikulum->nama_kurikulum }}
        &nbsp;|&nbsp; Dicetak dari SI-OBE &nbsp;|&nbsp; {{ now()->format('d M Y, H:i') }}
    </div>

</div>
</body>
</html>
