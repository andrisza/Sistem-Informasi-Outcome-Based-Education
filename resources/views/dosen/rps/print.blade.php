<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPS — {{ $mk->nama_mk ?? '' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000;
            background: #fff;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm 15mm 15mm 20mm;
        }

        /* ── Print button (tidak ikut cetak) ─────────────────── */
        .print-bar {
            position: fixed; top: 0; left: 0; right: 0;
            background: #1e293b; color: #fff;
            padding: 8px 20px;
            display: flex; align-items: center; gap: 12px;
            z-index: 999; font-family: sans-serif; font-size: 13px;
        }
        .btn-print {
            background: #f59e0b; color: #fff; border: none;
            padding: 6px 18px; border-radius: 6px; cursor: pointer;
            font-size: 13px; font-weight: 600;
        }
        .btn-back {
            background: transparent; color: #cbd5e1; border: 1px solid #475569;
            padding: 6px 14px; border-radius: 6px; cursor: pointer;
            font-size: 13px; text-decoration: none;
        }

        @media screen { .page { margin-top: 48px; box-shadow: 0 4px 20px rgba(0,0,0,.15); } }
        @media print  { .print-bar { display: none !important; } body { margin: 0; } .page { margin: 0; padding: 10mm 10mm 10mm 15mm; } }

        /* ── Table styles ────────────────────────────────────── */
        table { border-collapse: collapse; width: 100%; }
        td, th {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: top;
            line-height: 1.4;
        }
        th { font-weight: bold; }

        /* ── Header RPS ──────────────────────────────────────── */
        .header-table td { font-size: 9pt; }
        .institution-name { text-align: center; font-weight: bold; }
        .rps-title {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            padding: 4px;
            background: #d1d5db;
        }

        /* ── Section titles ──────────────────────────────────── */
        .section-label {
            font-weight: bold;
            font-size: 9pt;
            background: #f3f4f6;
        }
        .subsection-label { font-weight: bold; font-size: 9pt; }

        /* ── Approval row ────────────────────────────────────── */
        .approval-cell { text-align: center; }
        .ttd-box { min-height: 50px; }

        /* ── Weekly table ────────────────────────────────────── */
        .week-table th { background: #f59e0b; color: #000; text-align: center; font-size: 8.5pt; }
        .week-table td { font-size: 8.5pt; }
        .week-no { text-align: center; font-weight: bold; width: 20px; }

        /* ── Misc ────────────────────────────────────────────── */
        .italic { font-style: italic; color: #555; }
        .mt-4 { margin-top: 8px; }
        pre, .pre { white-space: pre-wrap; font-family: inherit; font-size: 9pt; }
    </style>
</head>
<body>

{{-- Print toolbar --}}
<div class="print-bar">
    <button class="btn-print" onclick="window.print()">🖨 Cetak / Simpan PDF</button>
    <a href="{{ route('dosen.rps.show', $rps) }}" class="btn-back">← Kembali</a>
    <span style="color:#94a3b8">RPS — {{ $mk->nama_mk ?? '' }} | {{ $rps->semester->nama ?? '' }}</span>
</div>

<div class="page">

{{-- ═══════════════════════════════════════════════════════════════════════
     BAGIAN 1: HEADER DOKUMEN
═══════════════════════════════════════════════════════════════════════ --}}
<table class="header-table">
    <tr>
        <td rowspan="3" style="width:60px;text-align:center;vertical-align:middle;font-size:9pt;font-weight:bold;">LOGO</td>
        <td class="institution-name">{{ strtoupper($rps->nama_perguruan_tinggi ?? 'NAMA PERGURUAN TINGGI') }}</td>
        <td rowspan="3" style="width:80px;text-align:center;vertical-align:middle;">
            <div style="font-size:8pt;font-weight:bold;">KODE<br>DOKUMEN</div>
            <div style="font-size:8pt;margin-top:4px;font-family:monospace;">{{ $rps->kode_dokumen }}</div>
        </td>
    </tr>
    <tr>
        <td class="institution-name">{{ strtoupper($rps->nama_fakultas ?? 'NAMA FAKULTAS') }}</td>
    </tr>
    <tr>
        <td class="institution-name">PROGRAM STUDI S1 SISTEM INFORMASI</td>
    </tr>
    <tr>
        <td colspan="3" class="rps-title">RENCANA PEMBELAJARAN SEMESTER</td>
    </tr>
</table>

{{-- Info MK + Bobot + Semester + Tanggal --}}
<table>
    <tr>
        <th style="width:22%;background:#e5e7eb">MATA KULIAH (MK)</th>
        <th style="width:12%;background:#e5e7eb">Kode</th>
        <th style="width:18%;background:#e5e7eb">Bahan Kajian (BK)</th>
        <th style="background:#e5e7eb" colspan="2">BOBOT (sks)</th>
        <th style="width:12%;background:#e5e7eb">SEMESTER</th>
        <th style="width:16%;background:#e5e7eb">Tanggal Penyusunan</th>
    </tr>
    <tr>
        <th style="background:#e5e7eb;text-align:center;font-size:8pt;"></th>
        <th style="background:#e5e7eb;text-align:center;font-size:8pt;"></th>
        <th style="background:#e5e7eb;text-align:center;font-size:8pt;"></th>
        <th style="background:#e5e7eb;text-align:center;font-size:8pt;width:9%">T [Teori]</th>
        <th style="background:#e5e7eb;text-align:center;font-size:8pt;width:9%">P [Praktikum]</th>
        <th style="background:#e5e7eb;text-align:center;font-size:8pt;"></th>
        <th style="background:#e5e7eb;text-align:center;font-size:8pt;"></th>
    </tr>
    <tr>
        <td>{{ $mk->nama_mk ?? '—' }}</td>
        <td style="text-align:center;font-weight:bold;">{{ $mk->kode_mk ?? '—' }}</td>
        <td>
            @forelse ($mk->bahanKajian ?? [] as $bk)
                <div>{{ $loop->iteration }}. {{ $bk->nama_bk }}</div>
            @empty
                <span class="italic">—</span>
            @endforelse
        </td>
        <td style="text-align:center">{{ $mk->sks_teori ?? 0 }}</td>
        <td style="text-align:center">{{ $mk->sks_praktikum ?? 0 }}</td>
        <td style="text-align:center">{{ $mk->semester ?? '—' }}</td>
        <td style="text-align:center">{{ $rps->tanggal_penyusunan?->format('d/m/Y') ?? '—' }}</td>
    </tr>
</table>

{{-- Pengesahan --}}
<table class="mt-4">
    <tr>
        <td rowspan="2" class="section-label" style="width:20%;vertical-align:middle">PENGESAHAN</td>
        <th style="text-align:center;background:#e5e7eb">Dosen Pengembang RPS</th>
        <th style="text-align:center;background:#e5e7eb">Koordinator BK</th>
        <th style="text-align:center;background:#e5e7eb">Ka Prodi</th>
    </tr>
    <tr>
        <td class="approval-cell" style="height:70px">
            <div class="ttd-box">TTD</div>
            <div style="margin-top:4px">{{ $rps->dosenPengembang?->name ?? '________________________________' }}</div>
        </td>
        <td class="approval-cell" style="height:70px">
            @if ($rps->koordinatorBk)
                <div class="ttd-box">TTD</div>
                <div style="margin-top:4px">{{ $rps->koordinatorBk->name }}</div>
            @else
                <div class="italic" style="font-size:8pt">(jika ada)</div>
            @endif
        </td>
        <td class="approval-cell" style="height:70px">
            <div class="ttd-box">TTD</div>
            <div style="margin-top:4px">{{ $rps->kaprodiPengesah?->name ?? '________________________________' }}</div>
        </td>
    </tr>
</table>

{{-- ═══════════════════════════════════════════════════════════════════════
     BAGIAN 2: CAPAIAN PEMBELAJARAN
═══════════════════════════════════════════════════════════════════════ --}}
<table class="mt-4">
    <tr>
        <td rowspan="{{ $cpmkList->count() + $subCpmkAll->count() + 6 }}"
            style="width:20%;font-weight:bold;vertical-align:top">Capaian<br>Pembelajaran</td>
        <td colspan="2" class="subsection-label">CPL-PRODI yang dibebankan pada MK</td>
    </tr>
    @forelse ($mk->cplProdi ?? [] as $cpl)
    <tr>
        <td style="width:14%;font-size:8.5pt;font-weight:bold">{{ $cpl->kode_cpl }}</td>
        <td style="font-size:8.5pt;font-style:italic">{{ $cpl->deskripsi }}</td>
    </tr>
    @empty
    <tr><td colspan="2" class="italic">Belum ada CPL Prodi yang dipetakan.</td></tr>
    @endforelse

    <tr><td colspan="2" class="subsection-label">Capaian Pembelajaran Mata Kuliah (CPMK)</td></tr>
    @forelse ($cpmkList as $cpmk)
    <tr>
        <td style="font-size:8.5pt;font-weight:bold">{{ $cpmk->kode_cpmk }}</td>
        <td style="font-size:8.5pt">{{ $cpmk->deskripsi }}</td>
    </tr>
    @empty
    <tr><td colspan="2" class="italic">Belum ada CPMK.</td></tr>
    @endforelse

    <tr><td colspan="2" class="subsection-label">Kemampuan akhir tiap tahapan belajar (Sub-CPMK)</td></tr>
    @forelse ($subCpmkAll as $sc)
    <tr>
        <td style="font-size:8.5pt;font-weight:bold">{{ $sc->kode_sub_cpmk }}</td>
        <td style="font-size:8.5pt">{{ $sc->deskripsi }}</td>
    </tr>
    @empty
    <tr><td colspan="2" class="italic">Belum ada Sub-CPMK.</td></tr>
    @endforelse

    {{-- Korelasi CPMK ↔ Sub-CPMK --}}
    <tr><td colspan="2" class="subsection-label">Korelasi CPMK terhadap Sub-CPMK</td></tr>
    <tr>
        <td colspan="2" style="padding:0">
            @if ($cpmkList->isNotEmpty() && $subCpmkAll->isNotEmpty())
            <table style="width:100%;border:none">
                <tr>
                    <td style="border-right:1px solid #000;border-bottom:1px solid #000;width:30%;font-size:8pt"></td>
                    @foreach ($subCpmkAll as $sc)
                        <td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;font-weight:bold;font-size:7.5pt">
                            {{ $sc->kode_sub_cpmk }}
                        </td>
                    @endforeach
                </tr>
                @foreach ($cpmkList as $cpmk)
                <tr>
                    <td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:bold;font-size:8pt;padding:2px 4px">
                        {{ $cpmk->kode_cpmk }}
                    </td>
                    @foreach ($subCpmkAll as $sc)
                        <td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;font-size:8pt">
                            {{ $sc->id_cpmk == $cpmk->id ? '✓' : '' }}
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </table>
            @else
                <div class="italic" style="padding:4px;font-size:8pt">—</div>
            @endif
        </td>
    </tr>
</table>

{{-- ═══════════════════════════════════════════════════════════════════════
     BAGIAN 3: DESKRIPSI, PUSTAKA, DOSEN PENGAMPU
═══════════════════════════════════════════════════════════════════════ --}}
<table class="mt-4">
    <tr>
        <td class="section-label" style="width:20%">Deskripsi singkat MK</td>
        <td class="pre">{{ $rps->deskripsi_mk ?: '—' }}</td>
    </tr>
    <tr>
        <td class="section-label" style="vertical-align:top">Kajian:<br>Materi Pembelajaran</td>
        <td class="pre">{{ $rps->materi_pembelajaran ?: '—' }}</td>
    </tr>
    <tr>
        <td class="section-label" rowspan="4" style="vertical-align:top">Pustaka</td>
        <td class="subsection-label">Utama :</td>
    </tr>
    <tr>
        <td class="pre italic">{{ $rps->pustaka_utama ?: '—' }}</td>
    </tr>
    <tr>
        <td class="subsection-label">Pendukung :</td>
    </tr>
    <tr>
        <td class="pre italic">{{ $rps->pustaka_pendukung ?: '—' }}</td>
    </tr>
    <tr>
        <td class="section-label">Dosen Pengampu</td>
        <td>{{ $rps->dosenPengembang?->name ?? '—' }}</td>
    </tr>
    <tr>
        <td class="section-label">Mata Kuliah Prasyarat</td>
        <td>{{ $mk->kode_prasyarat ?? '—' }}</td>
    </tr>
</table>

{{-- ═══════════════════════════════════════════════════════════════════════
     BAGIAN 4: RENCANA PERTEMUAN
═══════════════════════════════════════════════════════════════════════ --}}
<table class="week-table mt-4">
    <thead>
        <tr>
            <th style="width:5%">Minggu ke-</th>
            <th style="width:12%">Sub CPMK</th>
            <th style="width:16%">Indikator</th>
            <th style="width:14%">Kriteria &amp; Teknik</th>
            <th colspan="2">Bentuk Pembelajaran;</th>
            <th style="width:20%">Materi Pembelajaran</th>
            <th style="width:8%">Bobot Penilaian (%)</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="width:10%">luring</th>
            <th style="width:10%">daring</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mingguList as $item)
        @php $p = $item['data']; @endphp
        <tr>
            <td class="week-no">{{ $item['minggu_ke'] }}</td>
            <td style="text-align:center;font-weight:bold;font-size:8pt">
                {{ $p?->subCpmk?->kode_sub_cpmk ?? '' }}
            </td>
            <td class="pre" style="font-size:8pt">{{ $p?->indikator_penilaian ?? '' }}</td>
            <td class="pre" style="font-size:8pt">{{ $p?->kriteria_teknik ?? '' }}</td>
            <td style="font-size:8pt">{{ $p?->bentuk_luring ?? '' }}</td>
            <td style="font-size:8pt">{{ $p?->bentuk_daring ?? '' }}</td>
            <td class="pre" style="font-size:8pt">{{ $p?->materi_pembelajaran ?? '' }}</td>
            <td style="text-align:center;font-size:8pt">{{ $p?->bobot_penilaian ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>{{-- end .page --}}
</body>
</html>
