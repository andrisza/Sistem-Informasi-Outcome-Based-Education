@extends('layouts.app')

@section('title', 'Ketercapaian PL – ' . $kurikulum->kode)
@section('header', 'Ketercapaian Profil Lulusan (PL)')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Ketercapaian PL</span>
@endsection

@section('content')

@if ($plList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        Profil Lulusan belum diisi untuk kurikulum ini.
    </div>
@else

<div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-xs text-blue-800">
    Data ketercapaian PL diperoleh dari agregasi <code>hasil_pl</code> mahasiswa. Nilai ditampilkan sebagai rata-rata capaian.
</div>

{{-- Tabel ketercapaian --}}
<div class="rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr style="background:#1e3a5f" class="text-white">
                <th class="px-4 py-3 text-left text-xs font-bold border border-slate-600 w-24">Kode PL</th>
                <th class="px-4 py-3 text-left text-xs font-bold border border-slate-600">Deskripsi PL</th>
                <th class="px-4 py-3 text-center text-xs font-bold border border-slate-600 w-28">Nilai Rata-rata</th>
                <th class="px-4 py-3 text-center text-xs font-bold border border-slate-600 w-24">Tercapai</th>
                <th class="px-4 py-3 text-center text-xs font-bold border border-slate-600 w-24">Total Data</th>
                <th class="px-4 py-3 text-left text-xs font-bold border border-slate-600">Bar Capaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($plList as $pl)
                @php
                    $hasil   = $hasilPerPl->get($pl->id);
                    $nilai   = $hasil ? round($hasil->rata_nilai, 1) : null;
                    $tercapai= $hasil ? $hasil->tercapai_count : 0;
                    $total   = $hasil ? $hasil->total_record : 0;
                    $persen  = $nilai !== null ? min(100, $nilai) : 0;
                    $barCls  = $persen >= 70 ? 'bg-green-500' : ($persen >= 50 ? 'bg-amber-500' : 'bg-red-400');
                @endphp
                <tr class="border-b border-gray-100 hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50/40' : '' }}">
                    <td class="px-4 py-3 border border-gray-100">
                        <span class="font-mono font-bold text-amber-800 text-xs bg-amber-100 px-2 py-0.5 rounded">{{ $pl->kode_pl }}</span>
                    </td>
                    <td class="px-4 py-3 border border-gray-100 text-gray-700 text-sm">{{ Str::limit($pl->deskripsi, 80) }}</td>
                    <td class="px-4 py-3 border border-gray-100 text-center">
                        @if ($nilai !== null)
                            <span class="font-bold text-sm {{ $nilai >= 70 ? 'text-green-700' : ($nilai >= 50 ? 'text-amber-700' : 'text-red-700') }}">
                                {{ $nilai }}
                            </span>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 border border-gray-100 text-center text-sm font-medium text-gray-700">
                        {{ $tercapai }}
                    </td>
                    <td class="px-4 py-3 border border-gray-100 text-center text-sm text-gray-500">
                        {{ $total }}
                    </td>
                    <td class="px-4 py-3 border border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-100 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full transition-all {{ $barCls }}" style="width: {{ $persen }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-8 text-right">{{ $persen }}%</span>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- CSS-only bar chart --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
    <h3 class="font-semibold text-gray-800 text-sm mb-4">Grafik Ketercapaian PL</h3>
    <div class="space-y-3">
        @foreach ($plList as $pl)
            @php
                $hasil  = $hasilPerPl->get($pl->id);
                $nilai  = $hasil ? round($hasil->rata_nilai, 1) : 0;
                $persen = min(100, $nilai);
                $barCls = $persen >= 70 ? 'bg-green-500' : ($persen >= 50 ? 'bg-amber-500' : 'bg-red-400');
            @endphp
            <div class="flex items-center gap-3">
                <div class="w-16 text-right">
                    <span class="font-mono text-xs font-bold text-amber-800">{{ $pl->kode_pl }}</span>
                </div>
                <div class="flex-1 bg-gray-100 rounded-full h-4 overflow-hidden">
                    <div class="h-4 rounded-full {{ $barCls }} flex items-center justify-end pr-1 transition-all"
                         style="width: {{ max(5, $persen) }}%">
                        @if ($persen >= 20)
                            <span class="text-white text-[9px] font-bold">{{ $nilai }}</span>
                        @endif
                    </div>
                </div>
                <div class="w-8 text-xs text-gray-500">{{ $persen }}%</div>
            </div>
        @endforeach
    </div>
    <div class="flex items-center gap-4 mt-4 pt-3 border-t border-gray-100 text-xs">
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-green-500 inline-block"></span> ≥70% Tercapai</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-500 inline-block"></span> 50–69% Parsial</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-400 inline-block"></span> &lt;50% Belum Tercapai</span>
    </div>
</div>

@endif
@endsection
