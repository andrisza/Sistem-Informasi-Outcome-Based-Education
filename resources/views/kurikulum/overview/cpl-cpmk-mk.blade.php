@extends('layouts.app')

@section('title', 'Pemetaan CPL – CPMK – MK')
@section('header', 'Tabel Pemetaan CPL – CPMK – MK')

@section('breadcrumb')
    <a href="{{ route('kurikulum.index') }}" class="hover:text-blue-600">Kurikulum</a>
    <span class="mx-1 text-gray-300">/</span>
    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="hover:text-blue-600">{{ $kurikulum->kode }}</a>
    <span class="mx-1 text-gray-300">/</span>
    <span class="text-gray-700 font-medium">Peta CPL–CPMK–MK</span>
@endsection

@section('content')

@if (empty($tableData))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-5 text-sm text-amber-800 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Data belum lengkap</p>
            <p class="text-xs mt-0.5">CPL Prodi dan CPMK harus diisi terlebih dahulu.</p>
        </div>
    </div>
@else

@include('layouts._search', ['target'=>'cpl-cpmk-mk-wrap','placeholder'=>'Cari CPL, MK, atau CPMK...','mode'=>'dim','rowSelector'=>'tbody tr'])
<div id="cpl-cpmk-mk-wrap" class="rounded-xl border border-gray-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="border-collapse text-xs w-full">
            {{-- ═══ HEADER ═══ --}}
            <thead>
                <tr style="background:#F59E0B">
                    <th class="px-3 py-3 text-center text-xs font-bold text-white border border-amber-400 w-10">No</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-400 w-20">CPL</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400" style="min-width:220px">Deskripsi CPL</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-400 w-24">Kode CPMK</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-white border border-amber-400" style="min-width:220px">CPMK</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-white border border-amber-400 w-32">MK</th>
                </tr>
            </thead>

            {{-- ═══ BODY ═══ --}}
            <tbody>
                @php
                    $no = 0;
                    $katColors = [
                        'Sikap'               => ['cplBg'=>'#eff6ff','cplTxt'=>'#1e40af','cpmkBg'=>'#f0f9ff'],
                        'Keterampilan Umum'   => ['cplBg'=>'#f0fdf4','cplTxt'=>'#065f46','cpmkBg'=>'#f0fdf4'],
                        'Keterampilan Khusus' => ['cplBg'=>'#f5f3ff','cplTxt'=>'#4c1d95','cpmkBg'=>'#faf5ff'],
                        'Pengetahuan'         => ['cplBg'=>'#fffbeb','cplTxt'=>'#78350f','cpmkBg'=>'#fefce8'],
                    ];
                @endphp

                @foreach ($tableData as $entry)
                    @php
                        $cpl      = $entry['cpl'];
                        $cpmkRows = $entry['cpmk_rows'];
                        $rowCount = $entry['row_count'];
                        $no++;
                        $kat = $cpl->kategori ?? 'Lainnya';
                        $cc  = $katColors[$kat] ?? ['cplBg'=>'#f9fafb','cplTxt'=>'#374151','cpmkBg'=>'#f9fafb'];
                    @endphp

                    @if (empty($cpmkRows))
                        {{-- CPL tanpa CPMK --}}
                        <tr>
                            <td class="px-3 py-3 text-center text-gray-500 border border-gray-200 font-medium align-middle">{{ $no }}</td>
                            <td class="px-4 py-3 text-center border border-gray-200 align-middle" style="background:{{ $cc['cplBg'] }}">
                                <span class="font-mono font-bold text-xs px-2 py-0.5 rounded"
                                      style="color:{{ $cc['cplTxt'] }};background:{{ $cc['cplBg'] }}"
                                      data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                      data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 border border-gray-200 leading-relaxed align-middle" style="background:{{ $cc['cplBg'] }}">
                                {{ $cpl->deskripsi }}
                            </td>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-400 border border-gray-200 italic text-xs">
                                Belum ada CPMK untuk CPL ini
                            </td>
                        </tr>
                    @else
                        @foreach ($cpmkRows as $ci => $cpmkRow)
                            <tr class="{{ $ci % 2 === 0 ? '' : '' }}" style="background:#fff">
                                {{-- No & CPL: hanya pada baris pertama (rowspan) --}}
                                @if ($ci === 0)
                                    <td class="px-3 py-3 text-center text-gray-500 border border-gray-200 font-bold align-middle"
                                        rowspan="{{ $rowCount }}"
                                        style="background:{{ $cc['cplBg'] }}">
                                        {{ $no }}
                                    </td>
                                    <td class="px-4 py-3 text-center border border-gray-200 align-middle"
                                        rowspan="{{ $rowCount }}"
                                        style="background:{{ $cc['cplBg'] }}">
                                        <span class="font-mono font-bold text-xs px-2 py-1 rounded"
                                              style="color:{{ $cc['cplTxt'] }};background:{{ $cc['cplBg'] }};border:1px solid currentColor"
                                              data-tooltip="{{ $cpl->kode_cpl }}: {{ $cpl->deskripsi }}"
                                              data-tip-label="CPL Prodi">{{ $cpl->kode_cpl }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700 border border-gray-200 leading-relaxed align-top text-xs"
                                        rowspan="{{ $rowCount }}"
                                        style="background:{{ $cc['cplBg'] }};min-width:220px">
                                        {{ $cpl->deskripsi }}
                                    </td>
                                @endif

                                {{-- Kode CPMK --}}
                                <td class="px-4 py-3 text-center border border-gray-200 align-middle" style="background:{{ $cc['cpmkBg'] }}">
                                    <span class="font-mono font-bold text-xs text-blue-800 bg-blue-100 px-2 py-0.5 rounded"
                                          data-tooltip="{{ $cpmkRow['kode'] }}: {{ $cpmkRow['deskripsi'] }}"
                                          data-tip-label="CPMK">{{ $cpmkRow['kode'] }}</span>
                                </td>

                                {{-- Deskripsi CPMK --}}
                                <td class="px-4 py-3 text-gray-700 border border-gray-200 leading-relaxed align-top text-xs" style="min-width:220px">
                                    {{ $cpmkRow['deskripsi'] }}
                                </td>

                                {{-- MK List --}}
                                <td class="px-4 py-3 text-center border border-gray-200 align-middle">
                                    @if ($cpmkRow['mk_list']->isNotEmpty())
                                        <div class="flex flex-wrap gap-1 justify-center">
                                            @foreach ($cpmkRow['mk_list'] as $mk)
                                                <span class="font-mono font-bold text-[10px] px-1.5 py-0.5 bg-amber-100 text-amber-800 rounded cursor-help"
                                                      data-tooltip="{{ $mk->kode_mk }}: {{ $mk->nama_mk }} (Smt {{ $mk->semester }}, {{ $mk->sks_total }} SKS)"
                                                      data-tip-label="Mata Kuliah">{{ $mk->kode_mk }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 text-xs text-gray-400 flex flex-wrap gap-4">
    <span>· Hover kode untuk melihat deskripsi lengkap</span>
    <span>· CPL dikelompokkan berdasarkan kategori (Sikap → KU → KK → Pengetahuan)</span>
    @php $totalCpmk = array_sum(array_map(fn($e) => count($e['cpmk_rows']), $tableData)); @endphp
    <span class="ml-auto font-medium text-gray-600">{{ count($tableData) }} CPL · {{ $totalCpmk }} CPMK</span>
</div>

@endif

@endsection

@push('scripts')
@include('layouts._pivot-tooltip')
@endpush
