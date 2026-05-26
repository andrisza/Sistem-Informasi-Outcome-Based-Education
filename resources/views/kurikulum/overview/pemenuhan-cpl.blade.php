@extends('layouts.app')

@section('title', 'Peta Pemenuhan CPL')
@section('header', 'Peta Pemenuhan CPL')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Peta Pemenuhan CPL</span>
@endsection

@section('content')

<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-4 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
        <p class="font-semibold mb-0.5">Ringkasan capaian per CPL Prodi</p>
        <p>Untuk setiap CPL, ditampilkan MK yang memetakannya (melalui BK) beserta jumlah CPMK yang sudah dibuat. CPL tanpa MK perlu di-mapping di matriks CPL↔BK dan MK↔BK.</p>
    </div>
</div>

<div class="space-y-3">
@foreach ($cplList as $cpl)
    @php $list = $pemenuhan[$cpl->id] ?? []; @endphp
    <div class="bg-white border {{ count($list) ? 'border-gray-200' : 'border-amber-200' }} rounded-xl shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-4 py-2.5 {{ count($list) ? 'bg-violet-50' : 'bg-amber-50' }}">
            <span class="font-mono font-bold text-violet-800 text-xs bg-white px-2 py-0.5 rounded cursor-help"
                  data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                  data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
            <span class="text-xs text-gray-700 leading-snug flex-1">{{ \Str::limit($cpl->deskripsi, 120) }}</span>
            <span class="text-xs font-semibold {{ count($list) ? 'text-violet-700' : 'text-amber-700' }}">
                {{ count($list) }} MK
            </span>
        </div>

        @if (count($list))
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500">
                            <th class="px-3 py-2 text-left font-semibold w-16">Smt</th>
                            <th class="px-3 py-2 text-left font-semibold w-24">Kode MK</th>
                            <th class="px-3 py-2 text-left font-semibold">Nama Mata Kuliah</th>
                            <th class="px-3 py-2 text-left font-semibold w-48">Via Bahan Kajian</th>
                            <th class="px-3 py-2 text-center font-semibold w-20">CPMK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $row)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-3 py-2 text-gray-500">{{ $row['mk']->semester }}</td>
                                <td class="px-3 py-2">
                                    <span class="font-mono font-semibold text-blue-700 cursor-help"
                                          data-tooltip="{{ $row['mk']->kode_mk }}: {{ $row['mk']->nama_mk }} ({{ $row['mk']->sks_total }} SKS)"
                                          data-tip-label="Mata Kuliah">{{ $row['mk']->kode_mk }}</span>
                                </td>
                                <td class="px-3 py-2 text-gray-700">{{ $row['mk']->nama_mk }}</td>
                                <td class="px-3 py-2">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($row['bk_codes'] as $bk)
                                            <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[10px] font-mono">{{ $bk }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold
                                        {{ $row['cpmk_count'] > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $row['cpmk_count'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-4 py-4 text-xs text-amber-700">
                Belum dipetakan ke MK manapun. Lengkapi matriks
                <a href="{{ route('kurikulum.pivot.cpl-bk', $kurikulum) }}" class="underline font-medium">CPL↔BK</a> dan
                <a href="{{ route('kurikulum.pivot.mk-bk', $kurikulum) }}" class="underline font-medium">MK↔BK</a>.
            </div>
        @endif
    </div>
@endforeach
</div>

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
