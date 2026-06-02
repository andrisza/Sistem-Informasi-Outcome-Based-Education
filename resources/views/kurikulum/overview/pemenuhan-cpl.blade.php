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

{{-- Tab switcher --}}
<div class="flex gap-2 mb-4">
    <button id="tab-peta" onclick="switchTab('peta')"
            class="px-4 py-2 text-sm font-medium rounded-lg bg-amber-500 text-white transition-colors">
        Peta Visual (Semester)
    </button>
    <button id="tab-detail" onclick="switchTab('detail')"
            class="px-4 py-2 text-sm font-medium rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
        Detail per CPL
    </button>
</div>

{{-- ===== TAB 1: PETA VISUAL ===== --}}
<div id="content-peta">

@if ($cplList->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">
        CPL Prodi belum diisi. <a href="{{ route('kurikulum.cpl-prodi.index', $kurikulum) }}" class="underline font-medium">Tambah CPL &rarr;</a>
    </div>
@else

<div class="rounded-xl border border-amber-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs" style="min-width: max-content">
            <thead>
                <tr style="background:#F59E0B">
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400 sticky left-0 z-20" style="min-width:110px">
                        CPL
                    </th>
                    @foreach ($semesterRange as $smt)
                        <th style="background:#F59E0B;min-width:130px" class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-400">
                            Semester {{ $smt }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($cplList as $cpl)
                    @php $rowBg = $loop->even ? '#fffbeb' : '#fff'; @endphp
                    <tr style="background:{{ $rowBg }}">
                        <td class="px-3 py-3 border border-amber-200 sticky left-0 z-10 align-top" style="background:{{ $loop->even ? '#fef3c7' : '#fffbeb' }};min-width:110px">
                            <span class="font-mono font-bold text-violet-800 text-xs cursor-help block"
                                  data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                  data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                            <span class="text-gray-400 text-[10px] block mt-0.5 leading-snug">{{ \Str::limit($cpl->deskripsi, 35) }}</span>
                        </td>
                        @foreach ($semesterRange as $smt)
                            @php $mks = $peta[$cpl->id][$smt] ?? []; @endphp
                            <td class="border border-amber-100 align-top p-1.5" style="min-width:130px;vertical-align:top">
                                @if (count($mks))
                                    <div class="space-y-1">
                                        @foreach ($mks as $mk)
                                            <div class="border border-amber-300 rounded bg-white px-2 py-1 cursor-help hover:bg-amber-50 transition-colors"
                                                 data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} ({{ $mk->sks_total }} SKS)"
                                                 data-tip-label="Mata Kuliah">
                                                <div class="font-mono font-bold text-blue-800 text-[10px]">{{ $mk->kode_mk }}:</div>
                                                <div class="text-gray-600 text-[10px] leading-snug">{{ \Str::limit($mk->nama_mk, 28) }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endif
</div>

{{-- ===== TAB 2: DETAIL PER CPL ===== --}}
<div id="content-detail" class="hidden">

<div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-4 text-xs text-blue-800">
    <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Untuk setiap CPL, ditampilkan MK yang memetakannya beserta jumlah CPMK. CPL tanpa MK perlu di-mapping di matriks CPL&harr;BK dan MK&harr;BK.</span>
</div>

<div class="space-y-3">
@foreach ($cplList as $cpl)
    @php $list = $pemenuhan[$cpl->id] ?? []; @endphp
    <div class="bg-white border {{ count($list) ? 'border-gray-200' : 'border-amber-200' }} rounded-xl shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-4 py-2.5 {{ count($list) ? 'bg-violet-50' : 'bg-amber-50' }}">
            <span class="font-mono font-bold text-violet-800 text-xs bg-white px-2 py-0.5 rounded cursor-help"
                  data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                  data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
            <span class="text-xs text-gray-700 leading-snug flex-1">{{ $cpl->deskripsi }}</span>
            <span class="text-xs font-semibold {{ count($list) ? 'text-violet-700' : 'text-amber-700' }}">{{ count($list) }} MK</span>
        </div>
        @if (count($list))
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500">
                            <th class="px-3 py-2 text-left font-semibold w-12">Smt</th>
                            <th class="px-3 py-2 text-left font-semibold w-20">Kode MK</th>
                            <th class="px-3 py-2 text-left font-semibold">Nama Mata Kuliah</th>
                            <th class="px-3 py-2 text-left font-semibold w-40">Via Bahan Kajian</th>
                            <th class="px-3 py-2 text-center font-semibold w-16">CPMK</th>
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
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $row['cpmk_count'] > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $row['cpmk_count'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-4 py-4 text-xs text-amber-700">
                Belum dipetakan ke MK manapun. Lengkapi matriks
                <a href="{{ route('kurikulum.pivot.cpl-bk', $kurikulum) }}" class="underline font-medium">CPL&harr;BK</a> dan
                <a href="{{ route('kurikulum.pivot.mk-bk', $kurikulum) }}" class="underline font-medium">MK&harr;BK</a>.
            </div>
        @endif
    </div>
@endforeach
</div>
</div>

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
<script>
function switchTab(tab) {
    var peta   = document.getElementById('content-peta');
    var detail = document.getElementById('content-detail');
    var btnPeta   = document.getElementById('tab-peta');
    var btnDetail = document.getElementById('tab-detail');
    if (tab === 'peta') {
        peta.classList.remove('hidden');
        detail.classList.add('hidden');
        btnPeta.className   = 'px-4 py-2 text-sm font-medium rounded-lg bg-amber-500 text-white transition-colors';
        btnDetail.className = 'px-4 py-2 text-sm font-medium rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors';
    } else {
        peta.classList.add('hidden');
        detail.classList.remove('hidden');
        btnPeta.className   = 'px-4 py-2 text-sm font-medium rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors';
        btnDetail.className = 'px-4 py-2 text-sm font-medium rounded-lg bg-amber-500 text-white transition-colors';
    }
}
</script>
@endpush
