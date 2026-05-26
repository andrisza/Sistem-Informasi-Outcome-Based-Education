@extends('layouts.app')

@section('title', 'Dashboard Dosen')
@section('header', 'Dashboard')

@section('content')

{{-- Semester aktif banner --}}
@if ($semesterAktif)
<div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-3 flex items-center gap-3">
    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <p class="text-sm text-emerald-800">
        Semester aktif: <span class="font-semibold">{{ $semesterAktif->nama }} — {{ $semesterAktif->tahun_akademik }}</span>
        @if($semesterAktif->tanggal_mulai && $semesterAktif->tanggal_selesai)
            <span class="text-emerald-600 text-xs">({{ $semesterAktif->tanggal_mulai->format('d M Y') }} s.d. {{ $semesterAktif->tanggal_selesai->format('d M Y') }})</span>
        @endif
    </p>
</div>
@endif

{{-- Draft RPS alert --}}
@if ($rpsDraft > 0)
<div class="mb-5 bg-amber-50 border border-amber-200 rounded-xl px-5 py-3 flex items-center gap-3">
    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <p class="text-sm text-amber-800 flex-1">
        Anda memiliki <strong>{{ $rpsDraft }} RPS berstatus Draft</strong> yang belum diajukan ke Kaprodi.
    </p>
    <a href="{{ route('dosen.rps.index', ['status' => 'draft']) }}"
       class="text-xs font-medium text-amber-700 bg-amber-100 hover:bg-amber-200 px-3 py-1.5 rounded-lg transition-colors shrink-0">
        Lihat RPS →
    </a>
</div>
@endif

{{-- Stat cards --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    @php
        $stats = [
            ['label' => 'MK Diampu',         'value' => $jumlahMkAktif,        'sub' => 'semester ini',                 'color' => 'emerald'],
            ['label' => 'Total RPS',         'value' => $jumlahRps,            'sub' => 'yang saya kembangkan',         'color' => 'blue'],
            ['label' => 'Jurnal Bulan Ini',  'value' => $jumlahJurnalBulanIni, 'sub' => now()->translatedFormat('F Y'), 'color' => 'amber'],
            ['label' => 'RPS Draft',         'value' => $rpsDraft,             'sub' => 'belum diajukan',               'color' => 'violet'],
        ];
        $colorMap = [
            'emerald'=> ['icon' => 'bg-emerald-100 text-emerald-600', 'val' => 'text-emerald-700'],
            'blue'   => ['icon' => 'bg-blue-100 text-blue-600',       'val' => 'text-blue-700'],
            'amber'  => ['icon' => 'bg-amber-100 text-amber-600',     'val' => 'text-amber-700'],
            'violet' => ['icon' => 'bg-violet-100 text-violet-600',   'val' => 'text-violet-700'],
        ];
        $svgPaths = [
            'emerald' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'blue'    => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'amber'   => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
            'violet'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        ];
    @endphp

    @foreach ($stats as $stat)
        @php $c = $colorMap[$stat['color']]; @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $c['icon'] }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $svgPaths[$stat['color']] }}"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xl font-bold {{ $c['val'] }}">{{ $stat['value'] }}</p>
                <p class="text-xs font-medium text-gray-700 mt-0.5 leading-tight">{{ $stat['label'] }}</p>
                <p class="text-xs text-gray-400">{{ $stat['sub'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- MK yang diampu semester aktif --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 text-sm">Mata Kuliah yang Diampu Semester Ini</h3>
        <a href="{{ route('dosen.pengampuan') }}"
           class="text-xs text-emerald-600 hover:underline font-medium">Lihat semua →</a>
    </div>

    @if ($pengampuanAktif->isEmpty())
        <div class="px-5 py-10 text-center text-sm text-gray-400">
            <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Belum ada mata kuliah yang diampu pada semester aktif.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">SKS</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peran</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($pengampuanAktif as $p)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-gray-800">{{ $p->mataKuliah->nama_mk ?? '—' }}</p>
                            <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $p->mataKuliah->kode_mk ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-center text-gray-600">
                            {{ $p->mataKuliah->sks_total ?? '—' }}
                        </td>
                        <td class="px-4 py-3.5">
                            @if ($p->is_koordinator)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Koordinator</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Pengajar</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('dosen.rps.index', ['semester' => $p->id_semester]) }}"
                                   class="text-xs text-blue-600 hover:text-blue-700 font-medium hover:underline">RPS</a>
                                <span class="text-gray-200">|</span>
                                <a href="{{ route('dosen.jurnal.index') }}"
                                   class="text-xs text-amber-600 hover:text-amber-700 font-medium hover:underline">Jurnal</a>
                                <span class="text-gray-200">|</span>
                                <a href="{{ route('dosen.nilai.index') }}"
                                   class="text-xs text-emerald-600 hover:text-emerald-700 font-medium hover:underline">Nilai</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
