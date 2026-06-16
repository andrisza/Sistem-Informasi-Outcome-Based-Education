@extends('layouts.app')
@section('title', 'Detail RPS — ' . ($rps->mataKuliah->nama_mk ?? ''))
@section('header', 'Detail RPS')

@section('breadcrumb')
    <a href="{{ route('dosen.rps.index') }}" class="hover:text-blue-600">RPS Saya</a>
    <span class="mx-1">/</span>
    <span class="text-gray-700 font-medium">{{ $rps->mataKuliah->kode_mk ?? '' }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('dosen.rps.print', $rps) }}" target="_blank"
       class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Cetak RPS
    </a>
    @if ($rps->status === 'draft')
        <a href="{{ route('dosen.rps.edit', $rps) }}"
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <form method="POST" action="{{ route('dosen.rps.submit', $rps) }}"
              onsubmit="return confirm('Ajukan RPS ini untuk ditinjau Kaprodi?')" class="inline">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Submit ke Kaprodi
            </button>
        </form>
    @endif
@endsection

@section('content')

@php
    $badgeClass = match($rps->status) {
        'draft'    => 'bg-gray-100 text-gray-600',
        'review'   => 'bg-amber-100 text-amber-700',
        'disahkan' => 'bg-green-100 text-green-700',
        default    => 'bg-gray-100 text-gray-600',
    };
    $badgeLabel = ['draft'=>'Draft','review'=>'Review','disahkan'=>'Disahkan'][$rps->status] ?? $rps->status;
@endphp

{{-- Header card --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <h2 class="text-base font-bold text-gray-900">{{ $rps->mataKuliah->nama_mk ?? '—' }}</h2>
            <p class="text-sm text-gray-500 font-mono mt-0.5">{{ $rps->mataKuliah->kode_mk ?? '' }}</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $badgeClass }}">
            {{ $badgeLabel }}
        </span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm mt-4 pt-4 border-t border-gray-100">
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Semester Akademik</p>
            <p class="font-medium text-gray-700">{{ $rps->semester->nama ?? '—' }}</p>
            <p class="text-xs text-gray-400">{{ $rps->semester->tahun_akademik ?? '' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Kode Dokumen</p>
            <p class="font-medium text-gray-700 font-mono text-xs">{{ $rps->kode_dokumen }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-0.5">Tanggal Penyusunan</p>
            <p class="font-medium text-gray-700">{{ $rps->tanggal_penyusunan?->translatedFormat('d F Y') ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-0.5">SKS di RPS ini</p>
            @if ($rps->sks_teori !== null || $rps->sks_praktikum !== null)
            <p class="font-medium text-gray-700">
                {{ ($rps->sks_teori ?? 0) + ($rps->sks_praktikum ?? 0) }} SKS
                <span class="text-xs text-gray-400">(T={{ $rps->sks_teori ?? 0 }} P={{ $rps->sks_praktikum ?? 0 }})</span>
            </p>
            @else
            <p class="text-xs text-amber-600">Belum diisi — lengkapi di edit RPS</p>
            @endif
        </div>
    </div>
    @if ($rps->status === 'disahkan' && $rps->disahkan_pada)
    <div class="mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500">
        Disahkan: <span class="font-medium text-gray-700">{{ $rps->disahkan_pada->format('d M Y H:i') }}</span>
        @if ($rps->kaprodiPengesah) oleh <span class="font-medium">{{ $rps->kaprodiPengesah->name }}</span>@endif
    </div>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

{{-- Kolom kiri: isi konten --}}
<div class="lg:col-span-2 space-y-5">

    {{-- Capaian Pembelajaran --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Capaian Pembelajaran</h3>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-2">CPL-PRODI yang Dibebankan pada MK</p>
                @forelse ($mk->cplProdi ?? [] as $cpl)
                    <div class="flex gap-2 text-xs mb-1">
                        <span class="font-mono font-bold text-amber-700 w-16 shrink-0">{{ $cpl->kode_cpl }}</span>
                        <span class="text-gray-600">{{ $cpl->deskripsi }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 italic">Belum dipetakan.</p>
                @endforelse
            </div>
            <div>
                <p class="text-[10px] font-bold text-blue-700 uppercase tracking-wider mb-2">CPMK</p>
                @forelse ($cpmkList as $cpmk)
                    <div class="flex gap-2 text-xs mb-1">
                        <span class="font-mono font-bold text-blue-700 w-20 shrink-0">{{ $cpmk->kode_cpmk }}</span>
                        <span class="text-gray-600">{{ $cpmk->deskripsi }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 italic">Belum ada CPMK.</p>
                @endforelse
            </div>
            <div>
                <p class="text-[10px] font-bold text-green-700 uppercase tracking-wider mb-2">Sub-CPMK</p>
                @forelse ($subCpmkAll as $sc)
                    <div class="flex gap-2 text-xs mb-1">
                        <span class="font-mono font-bold text-green-700 w-24 shrink-0">{{ $sc->kode_sub_cpmk }}</span>
                        <span class="text-gray-600">{{ $sc->deskripsi }}</span>
                        @if($sc->bobot)<span class="ml-auto shrink-0 text-gray-400">{{ $sc->bobot }}%</span>@endif
                    </div>
                @empty
                    <p class="text-xs text-gray-400 italic">Belum ada Sub-CPMK.</p>
                @endforelse
            </div>
            <div>
                <p class="text-[10px] font-bold text-purple-700 uppercase tracking-wider mb-2">Korelasi CPMK terhadap Sub-CPMK</p>
                @if ($cpmkList->isNotEmpty() && $subCpmkAll->isNotEmpty())
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="border-collapse text-xs w-full">
                        <thead>
                            <tr>
                                <th class="border border-gray-200 bg-gray-50 px-2 py-1.5 text-left text-[10px] font-bold text-gray-500 sticky left-0 z-10">CPMK \ Sub-CPMK</th>
                                @foreach ($subCpmkAll as $sc)
                                    <th class="border border-gray-200 bg-green-50 px-2 py-1.5 text-center text-[10px] font-bold text-green-700 font-mono">{{ $sc->kode_sub_cpmk }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cpmkList as $cpmk)
                            <tr>
                                <th class="border border-gray-200 bg-blue-50 px-2 py-1.5 text-left text-[10px] font-bold text-blue-700 font-mono sticky left-0 z-10">{{ $cpmk->kode_cpmk }}</th>
                                @foreach ($subCpmkAll as $sc)
                                    <td class="border border-gray-200 text-center px-2 py-1.5 {{ $sc->id_cpmk == $cpmk->id ? 'text-emerald-600 font-bold' : 'text-gray-300' }}">
                                        {{ $sc->id_cpmk == $cpmk->id ? '✓' : '—' }}
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-xs text-gray-400 italic">Belum ada CPMK / Sub-CPMK untuk ditampilkan.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Deskripsi & Materi --}}
    @if ($rps->deskripsi_mk || $rps->materi_pembelajaran)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Deskripsi & Materi Pembelajaran</h3>
        </div>
        <div class="p-5 space-y-4">
            @if ($rps->deskripsi_mk)
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi Singkat MK</p>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $rps->deskripsi_mk }}</p>
            </div>
            @endif
            @if ($rps->materi_pembelajaran)
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Kajian: Materi Pembelajaran</p>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $rps->materi_pembelajaran }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Tabel pertemuan --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-sm">Rencana Pertemuan (16 Minggu)</h3>
            @if ($rps->status === 'draft')
            <a href="{{ route('dosen.rps.edit', $rps) }}" class="text-xs text-amber-600 hover:underline">Edit →</a>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr style="background:#F59E0B">
                        <th class="border border-amber-300 text-white font-bold px-3 py-2 text-center w-12">Minggu</th>
                        <th class="border border-amber-300 text-white font-bold px-3 py-2">Sub-CPMK</th>
                        <th class="border border-amber-300 text-white font-bold px-3 py-2">Indikator</th>
                        <th class="border border-amber-300 text-white font-bold px-3 py-2">Kriteria & Teknik</th>
                        <th class="border border-amber-300 text-white font-bold px-3 py-2">Luring</th>
                        <th class="border border-amber-300 text-white font-bold px-3 py-2">Daring</th>
                        <th class="border border-amber-300 text-white font-bold px-3 py-2">Materi</th>
                        <th class="border border-amber-300 text-white font-bold px-3 py-2 text-center w-14">Bobot</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mingguList as $item)
                    @php $p = $item['data']; @endphp
                    <tr class="{{ $item['minggu_ke'] % 2 === 0 ? 'bg-amber-50' : 'bg-white' }}">
                        <td class="border border-amber-100 text-center font-bold text-gray-700 py-2">{{ $item['minggu_ke'] }}</td>
                        <td class="border border-amber-100 px-2 py-2">
                            @if ($p?->subCpmk)
                                <span class="font-mono font-bold text-green-700">{{ $p->subCpmk->kode_sub_cpmk }}</span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="border border-amber-100 px-2 py-2 text-gray-600 max-w-xs leading-snug">{{ $p?->indikator_penilaian ?? '—' }}</td>
                        <td class="border border-amber-100 px-2 py-2 text-gray-600 max-w-xs leading-snug">{{ $p?->kriteria_teknik ?? '—' }}</td>
                        <td class="border border-amber-100 px-2 py-2 text-gray-600">{{ $p?->bentuk_luring ?? '—' }}</td>
                        <td class="border border-amber-100 px-2 py-2 text-gray-600">{{ $p?->bentuk_daring ?? '—' }}</td>
                        <td class="border border-amber-100 px-2 py-2 text-gray-600 max-w-xs leading-snug whitespace-pre-line">{{ $p?->materi_pembelajaran ?? '—' }}</td>
                        <td class="border border-amber-100 text-center font-mono text-gray-700">
                            {{ $p?->bobot_penilaian ? $p->bobot_penilaian . '%' : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>{{-- end col kiri --}}

{{-- Kolom kanan: pengesahan & pustaka --}}
<div class="space-y-5">

    {{-- Pengesahan --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Pengesahan</h3>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wider">Dosen Pengembang</p>
                <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $rps->dosenPengembang?->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wider">Koordinator BK</p>
                <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $rps->koordinatorBk?->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-wider">Ka Prodi</p>
                <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $rps->kaprodiPengesah?->name ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Institusi --}}
    @if ($rps->nama_perguruan_tinggi || $rps->nama_fakultas)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Institusi</h3>
        </div>
        <div class="p-5 space-y-2 text-sm">
            <div><p class="text-xs text-gray-400">Perguruan Tinggi</p><p class="font-medium">{{ $rps->nama_perguruan_tinggi ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-400">Fakultas</p><p class="font-medium">{{ $rps->nama_fakultas ?? '—' }}</p></div>
        </div>
    </div>
    @endif

    {{-- Pustaka --}}
    @if ($rps->pustaka_utama || $rps->pustaka_pendukung)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Pustaka</h3>
        </div>
        <div class="p-5 space-y-4">
            @if ($rps->pustaka_utama)
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Utama</p>
                <p class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">{{ $rps->pustaka_utama }}</p>
            </div>
            @endif
            @if ($rps->pustaka_pendukung)
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Pendukung</p>
                <p class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">{{ $rps->pustaka_pendukung }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>{{-- end col kanan --}}

</div>{{-- end grid --}}

@endsection
