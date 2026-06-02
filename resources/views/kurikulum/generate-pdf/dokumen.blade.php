<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumen Kurikulum – {{ $kurikulum->kode }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: serif; font-size: 12pt; color: #111; }
        .no-print { }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            .page-break { page-break-before: always; }
        }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #d1d5db; padding: 6px 10px; font-size: 10pt; }
        th { background: #1e3a5f; color: #fff; text-align: left; }
        .section-title { font-size: 14pt; font-weight: bold; border-bottom: 2px solid #1e3a5f; padding-bottom: 4px; margin-bottom: 12px; color: #1e3a5f; }
        .sub-section { font-size: 12pt; font-weight: bold; color: #374151; margin: 12px 0 6px; }
    </style>
</head>
<body class="bg-white">

{{-- Print button --}}
<div class="no-print p-4 bg-blue-50 border-b border-blue-200 flex items-center gap-4">
    <span class="text-sm font-medium text-blue-800">Dokumen Kurikulum – {{ $kurikulum->kode }}</span>
    <button onclick="window.print()"
            class="ml-auto bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        Cetak / Simpan PDF
    </button>
    <a href="{{ url()->previous() }}"
       class="text-sm text-gray-600 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        Kembali
    </a>
</div>

<div class="max-w-4xl mx-auto px-8 py-10">

    {{-- COVER --}}
    <div class="text-center mb-12">
        <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">Dokumen Kurikulum</p>
        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $kurikulum->nama_kurikulum }}</h1>
        <p class="text-base text-gray-600">{{ $kurikulum->program_studi }} — {{ $kurikulum->jenjang }}</p>
        <p class="text-sm text-gray-500 mt-1">Tahun {{ $kurikulum->tahun_mulai }}{{ $kurikulum->tahun_selesai ? '–' . $kurikulum->tahun_selesai : '' }}</p>
        <p class="text-xs text-gray-400 mt-3">Kode: {{ $kurikulum->kode }} &nbsp;|&nbsp; Status: {{ ucfirst($kurikulum->status) }}</p>
    </div>

    {{-- VISI MISI --}}
    @if ($kurikulum->visi || $kurikulum->misi)
    <div class="mb-8">
        <h2 class="section-title">Visi & Misi Program Studi</h2>
        @if ($kurikulum->visi)
        <div class="mb-3">
            <p class="sub-section">Visi</p>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $kurikulum->visi }}</p>
        </div>
        @endif
        @if ($kurikulum->misi)
        <div class="mb-3">
            <p class="sub-section">Misi</p>
            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $kurikulum->misi }}</p>
        </div>
        @endif
        @if ($kurikulum->tujuan)
        <div class="mb-3">
            <p class="sub-section">Tujuan</p>
            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $kurikulum->tujuan }}</p>
        </div>
        @endif
    </div>
    @endif

    {{-- PROFIL LULUSAN --}}
    <div class="mb-8 page-break">
        <h2 class="section-title">Profil Lulusan (PL)</h2>
        <table>
            <thead>
                <tr>
                    <th class="w-20">Kode PL</th>
                    <th>Deskripsi</th>
                    <th class="w-36">Kategori</th>
                    <th class="w-16">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kurikulum->pl as $pl)
                <tr>
                    <td class="font-mono font-bold text-amber-800">{{ $pl->kode_pl }}</td>
                    <td>{{ $pl->deskripsi }}</td>
                    <td>{{ $pl->kategori ?? '—' }}</td>
                    <td class="text-center">{{ ucfirst($pl->status) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-gray-400">Belum ada PL.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- CPL PRODI --}}
    <div class="mb-8">
        <h2 class="section-title">CPL Program Studi</h2>
        <table>
            <thead>
                <tr>
                    <th class="w-20">Kode CPL</th>
                    <th>Deskripsi</th>
                    <th class="w-24">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kurikulum->cplProdi as $cpl)
                <tr>
                    <td class="font-mono font-bold text-blue-800">{{ $cpl->kode_cpl }}</td>
                    <td>{{ $cpl->deskripsi }}</td>
                    <td>{{ $cpl->kategori ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-gray-400">Belum ada CPL.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- BAHAN KAJIAN --}}
    <div class="mb-8 page-break">
        <h2 class="section-title">Bahan Kajian (BK)</h2>
        <table>
            <thead>
                <tr>
                    <th class="w-20">Kode BK</th>
                    <th>Nama Bahan Kajian</th>
                    <th>Bidang Keilmuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kurikulum->bahanKajian as $bk)
                <tr>
                    <td class="font-mono font-bold text-emerald-800">{{ $bk->kode_bk }}</td>
                    <td>{{ $bk->nama_bk }}</td>
                    <td>{{ $bk->bidang_keilmuan ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-gray-400">Belum ada BK.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MATA KULIAH PER SEMESTER --}}
    <div class="mb-8 page-break">
        <h2 class="section-title">Mata Kuliah per Semester</h2>
        @foreach ($mkBySemester as $smt => $mks)
        <div class="mb-4">
            <p class="sub-section">Semester {{ $smt }}</p>
            <table>
                <thead>
                    <tr>
                        <th class="w-24">Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th class="w-16 text-center">T</th>
                        <th class="w-16 text-center">P</th>
                        <th class="w-16 text-center">SKS</th>
                        <th class="w-24">Kategori</th>
                        <th class="w-28">Prasyarat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mks as $mk)
                    <tr>
                        <td class="font-mono font-bold">{{ $mk->kode_mk }}</td>
                        <td>{{ $mk->nama_mk }}</td>
                        <td class="text-center">{{ $mk->sks_teori }}</td>
                        <td class="text-center">{{ $mk->sks_praktikum }}</td>
                        <td class="text-center font-bold">{{ ($mk->sks_teori ?? 0) + ($mk->sks_praktikum ?? 0) }}</td>
                        <td>{{ $mk->kategori_mk }}</td>
                        <td>{{ $mk->kode_prasyarat ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>

    {{-- CPMK per MK --}}
    <div class="mb-8 page-break">
        <h2 class="section-title">CPMK per Mata Kuliah</h2>
        @foreach ($kurikulum->mataKuliah as $mk)
            @if ($cpmkByMk->has($mk->id))
            <div class="mb-4">
                <p class="sub-section">{{ $mk->kode_mk }} – {{ $mk->nama_mk }}</p>
                <table>
                    <thead>
                        <tr>
                            <th class="w-24">Kode CPMK</th>
                            <th>Deskripsi</th>
                            <th class="w-20">Level Bloom</th>
                            <th class="w-24">CPL Terkait</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cpmkByMk[$mk->id] as $cpmk)
                        <tr>
                            <td class="font-mono font-bold text-xs">{{ $cpmk->kode_cpmk }}</td>
                            <td>{{ $cpmk->deskripsi }}</td>
                            <td class="text-center text-xs font-mono">{{ $cpmk->level_bloom ?? '—' }}</td>
                            <td class="font-mono text-xs text-blue-800">{{ $cpmk->cplProdi?->kode_cpl ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        @endforeach
    </div>

    {{-- Footer --}}
    <div class="border-t border-gray-300 pt-4 mt-8 text-center text-xs text-gray-400">
        Dicetak dari SI-OBE &nbsp;|&nbsp; {{ now()->format('d M Y H:i') }} &nbsp;|&nbsp; {{ $kurikulum->kode }}
    </div>

</div>
</body>
</html>
