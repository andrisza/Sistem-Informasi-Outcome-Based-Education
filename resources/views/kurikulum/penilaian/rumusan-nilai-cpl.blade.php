@extends('layouts.app')
@section('title', 'Rumusan Nilai Akhir CPL')
@section('header', 'Rumusan Nilai Akhir CPL')
@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Rumusan Nilai Akhir CPL</span>
@endsection

@section('header-actions')
    @include('layouts._export-button', ['route' => route('kurikulum.penilaian.rumusan-cpl.export', $kurikulum)])
@endsection

@section('content')

@if (empty($tableData))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800">CPL Prodi belum diisi.</div>
@else

@if (!$kurikulum->isArsip())
<form method="POST" action="{{ route('kurikulum.penilaian.rumusan-cpl.save', $kurikulum) }}">
@csrf
@endif

<div class="rounded-xl border border-blue-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs w-full">
            <thead>
                <tr style="background:#2563EB">
                    <th class="px-4 py-3 text-center font-bold text-white border border-blue-400 w-20">CPL</th>
                    <th class="px-4 py-3 text-center font-bold text-white border border-blue-400 w-20">MK</th>
                    <th class="px-4 py-3 text-center font-bold text-white border border-blue-400 w-28">CPMK</th>
                    <th class="px-4 py-3 text-center font-bold text-white border border-blue-400 w-28">Skor Maks</th>
                    <th class="px-4 py-3 text-center font-bold text-white border border-blue-400 w-20">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tableData as $entry)
                    @php
                        $cpl          = $entry['cpl'];
                        $mkRows       = $entry['mk_rows'];
                        $cplTotal     = $entry['cpl_total'];
                        $cplRowCount  = $entry['row_count'];
                        $cplRowStart  = true;
                        $totalRendered = 0;
                        $rowBg        = $loop->even ? '#eff6ff' : '#fff';
                        $totalColor   = $cplTotal > 0 ? 'text-blue-800 font-bold' : 'text-gray-400';
                    @endphp

                    @foreach ($mkRows as $mkEntry)
                        @php
                            $mk         = $mkEntry['mk'];
                            $cpmkRows   = $mkEntry['cpmk_rows'];
                            $mkRowStart = true;
                        @endphp

                        @foreach ($cpmkRows as $ri => $cpmkRow)
                            @php
                                $cpmk     = $cpmkRow['cpmk'];
                                $skorMaks = $cpmkRow['skor_maks'];
                                $totalRendered++;
                                $isCplFirst = $cplRowStart;
                                $isMkFirst  = $mkRowStart;
                                if ($cplRowStart) $cplRowStart = false;
                                if ($mkRowStart)  $mkRowStart  = false;
                            @endphp
                            <tr style="background:{{ $rowBg }}" class="hover:bg-blue-50/30">
                                @if ($isCplFirst)
                                    <td class="px-4 py-2.5 text-center border border-blue-100 align-middle"
                                        rowspan="{{ $cplRowCount }}"
                                        style="background:#dbeafe">
                                        <span class="font-mono font-bold text-blue-800 text-xs cursor-help"
                                              data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                              data-tip-label="CPL">{{ $cpl->kode_cpl }}</span>
                                    </td>
                                @endif
                                @if ($isMkFirst)
                                    <td class="px-4 py-2.5 text-center border border-blue-100 align-middle"
                                        rowspan="{{ count($cpmkRows) }}">
                                        <span class="font-mono font-bold text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded cursor-help"
                                              data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Smt {{ $mk->semester }})"
                                              data-tip-label="MK">{{ $mk->kode_mk }}</span>
                                    </td>
                                @endif
                                <td class="px-4 py-2.5 text-center border border-blue-100 align-middle">
                                    <span class="font-mono font-bold text-[10px] bg-violet-100 text-violet-800 px-1.5 py-0.5 rounded cursor-help"
                                          data-tooltip="{{ $cpmk->kode_cpmk }}: {{ $cpmk->deskripsi }}"
                                          data-tip-label="CPMK">{{ $cpmk->kode_cpmk }}</span>
                                </td>
                                <td class="px-4 py-2 text-center border border-blue-100 align-middle">
                                    @if (!$kurikulum->isArsip())
                                        <input type="number" name="skor_maks[{{ $cpmk->id }}]"
                                               value="{{ $skorMaks > 0 ? $skorMaks : '' }}"
                                               min="0" max="100" step="0.5"
                                               placeholder="0"
                                               class="w-20 border border-gray-300 rounded px-2 py-1 text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    @else
                                        <span class="font-bold text-blue-700">{{ $skorMaks > 0 ? $skorMaks : '—' }}</span>
                                    @endif
                                </td>
                                @if ($isCplFirst)
                                    <td class="px-4 py-2.5 text-center border border-blue-100 align-middle font-bold text-sm"
                                        rowspan="{{ $cplRowCount }}">
                                        <span class="{{ $totalColor }}">{{ $cplTotal > 0 ? $cplTotal : '—' }}</span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 flex flex-wrap gap-4 text-xs text-gray-400 items-center">
    <span>· Total per CPL = jumlah Skor Maks semua CPMK di bawah CPL tersebut</span>
    <span>· Skor Maks dapat diedit langsung atau melalui halaman Bobot Penilaian</span>
    @if (!$kurikulum->isArsip())
        <div class="ml-auto">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Simpan Skor Maks
            </button>
        </div>
    @endif
</div>

@if (!$kurikulum->isArsip())
</form>
@endif

@endif

@endsection
@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
